import "./styles.css";

// Validation
interface Validatable {
  value: string | number
  required?: boolean
  minLength?: number
  maxLength?: number
  min?: number
  max?: number
}

const validate = (input: Validatable) => {
  let isValid = true
  const { value, required, minLength, maxLength, min, max } = input
  if (required) {
    isValid = isValid && value.toString().length !== 0
  }
  if (minLength != null && typeof value === 'string') {
    isValid = isValid && value.length > minLength
  }
  if (maxLength != null && typeof value === 'string') {
    isValid = isValid && value.length > maxLength
  }
  if (min != null && typeof value === 'number') {
    isValid = isValid && value > min
  }
  if (max != null && typeof value === 'number') {
    isValid = isValid && value < max
  }
  return isValid
}

// decorators
const autobind = (target: any, methodName: string, descriptor: PropertyDescriptor) => {
  const original = descriptor.value
  const adjDescriptor: PropertyDescriptor = {
    configurable: true,
    get() { return original.bind(this) }
  }
  return adjDescriptor
}

interface Draggable {
  dragStartHandler(event: DragEvent): void
  dragStopHandler(event: DragEvent): void
}

interface DragTarget {
  dragOverHandler(event: DragEvent): void
  dropHandler(event: DragEvent): void
  dragLeaveHandler(event: DragEvent): void
}

enum Status {
  Active,
  Finished
}

class Project {
  constructor(
    public id: string,
    public title: string,
    public description: string,
    public people: number,
    public status: Status
  ) {
  }
}

type Listener<T> = (items: T[]) => void

class State<T> {
  protected listeners: Listener<T>[] = []

  addListener(listenerFunction: Listener<T>) {
    this.listeners.push(listenerFunction)
  }
}

class ProjectState extends State<Project> {
  private projects: Project[] = []
  private static instance: ProjectState

  static getInstance() {
    if (this.instance) return this.instance
    this.instance = new ProjectState()
    return this.instance
  }

  private constructor() {
    super()
  }

  addProject(title: string, description: string, people: number) {
    const newProject = new Project(
      String(Date.now()),
      title,
      description,
      people,
      Status.Active
    )
    this.projects.push(newProject)
    this.updateListeners()
  }

  updateStatus(id:string, status: Status) {
    const project = this.projects.find(item => item.id === id)
    if (project && project.status !== status) {
      project.status = status
      this.updateListeners()
    }
  }

  updateListeners() {
    for (const listenerFn of this.listeners) {
      listenerFn([...this.projects]) // do not mutate state
    }
  }
}

const state = ProjectState.getInstance()

// NOTE: let you know that this class is not for instanciate and only be used for inheritance
abstract class Component<T extends HTMLElement, U extends HTMLElement> {
  // NOTE: we use generics because renderElement and alement can have different types and we dont know 
  templateElement: HTMLTemplateElement
  renderElement: T
  element: U

  constructor(templateId: string, renderElementId: string, insertAtStart: boolean, newElementId?: string) {
    this.templateElement = document.getElementById(templateId) as HTMLTemplateElement // typecasting
    this.renderElement = document.getElementById(renderElementId) as T
    const templateNode = document.importNode(this.templateElement.content, true)
    this.element = templateNode.firstElementChild as U
    if (newElementId) this.element.id = newElementId
    this.attach(insertAtStart)
  }

  attach(insertAtStart: boolean) {
    this.renderElement.insertAdjacentElement(insertAtStart ? 'afterbegin' : 'beforeend', this.element)
  }

  // NOTE: this let you know that the implementation still needs these methods
  // that should be setted in where you inherit
  abstract configure(): void
  abstract renderContent(): void
}

class Item extends Component<HTMLUListElement, HTMLLIElement> implements Draggable {
  private project: Project

  get persons() {
    return this.project.people === 1 ? '1 person' : `${this.project.people} persons`
  }

  constructor(parentId: string, project: Project ) {
    super('single-project', parentId, false, project.id)
    this.project = project
    this.configure()
    this.renderContent()
  }

  configure() {
    this.element.addEventListener('dragstart', this.dragStartHandler)
    this.element.addEventListener('dragstop', this.dragStopHandler)
  }

  renderContent() {
    this.element.querySelector('h2')!.textContent = this.project.title
    this.element.querySelector('h3')!.textContent = this.persons + ' assigned'
    this.element.querySelector('p')!.textContent = this.project.description
  }

  @autobind
  dragStartHandler(event: DragEvent) {
    event.dataTransfer?.setData('text/plain', this.project.id)
    event.dataTransfer!.effectAllowed = 'move'
  }

  @autobind
  dragStopHandler() {

  }

}

class List extends Component<HTMLDivElement, HTMLElement> implements DragTarget {
  listID: string
  assignedProjects: Project[] = []

  constructor(private type: 'active' | 'finished') {
    super('project-list', 'app', false, `${type}-projects`)
    this.listID = `${this.type}-projects`
    this.configure()
    this.renderContent()
  }

  configure() {
    this.element.addEventListener('dragover', this.dragOverHandler)
    this.element.addEventListener('drop', this.dropHandler)
    this.element.addEventListener('dragleave', this.dragLeaveHandler)
    state.addListener((projects: Project[]) => {
      const filteredProjects = projects.filter(project => Status[project.status].toLowerCase() === this.type)
      this.assignedProjects = filteredProjects
      this.renderProjects()
    })
  }

  @autobind
  dragOverHandler(event: DragEvent) {
    if (event.dataTransfer?.types[0] === 'text/plain') {
      event.preventDefault() // NOTE: this is need to the drop works
      const listElement = this.element.querySelector('ul')
      listElement!.classList.add('droppable')
    }
  }

  @autobind
  dropHandler(event: DragEvent) {
    const id = event.dataTransfer!.getData('text/plain')
    state.updateStatus(id, this.type === 'active' ? Status.Active : Status.Finished)
  }

  @autobind
  dragLeaveHandler() {
    const listElement = this.element.querySelector('ul')
    listElement!.classList.remove('droppable')
  }

  renderContent() {
    this.element.querySelector('ul')!.id = `${this.listID}-list`
    this.element.querySelector('h2')!.textContent = `${this.type.toUpperCase()} PROJECTS`
  }

  private renderProjects() {
    const list = document.getElementById(`${this.listID}-list`)! as HTMLUListElement
    list.textContent = ''
    for (const project of this.assignedProjects) {
      new Item(`${this.listID}-list`, project)
    }
  } 
}

class Form extends Component<HTMLDivElement, HTMLFormElement>{
  titleElement: HTMLInputElement
  descriptionElement: HTMLInputElement
  peopleElement: HTMLInputElement

  constructor() {
    super('project-input', 'app', true, 'user-input')
    this.titleElement = this.element.querySelector('#title') as HTMLInputElement
    this.descriptionElement = this.element.querySelector('#description') as HTMLInputElement
    this.peopleElement = this.element.querySelector('#people') as HTMLInputElement
    this.configure()
  }

  configure() {
    this.element.addEventListener('submit', this.submitHandler)
  }

  renderContent() {}

  private clearInputs() {
    this.titleElement.value = ''
    this.descriptionElement.value = ''
    this.peopleElement.value = ''
  }

  private getFormData(): [string, string, number] | void {
    const title = this.titleElement.value
    const description = this.descriptionElement.value
    const people = this.peopleElement.value

    const validateTitle: Validatable = {
      value: title,
      required: true
    } 

    const validateDescription: Validatable = {
      value: description,
      required: true
    } 

    const validatePeople: Validatable = {
      value: people,
      required: true
    } 

    if (!validate(validateTitle) || !validate(validateDescription) || !validate(validatePeople)) {
      return alert('something is missing')
    }

    return [title, description, +people]
  } 

  @autobind
  private submitHandler(event: Event) {
    event.preventDefault()
    const data = this.getFormData()
    if (Array.isArray(data)) {
      state.addProject(...data)
      this.clearInputs()
    }
  }
}

new Form()
new List('active')
new List('finished')