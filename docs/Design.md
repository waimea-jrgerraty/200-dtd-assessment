# The Design of a Database-Linked Website for NCEA Level 2

Project Name: **Game Development Manager Website**

Project Author: **James Gerraty**

Assessment Standards: **91892** and **91893**


-------------------------------------------------

## System Requirements

### Identified Need or Problem

It is too hard to manage workflow in Game Development in a small team where each member has specialised roles using existing tooling. Current tools focus on general usage for any end user, which does not translate well to our team. It is too hard to split different tasks into different teams using singular categories and as a result, we find ourselves moving away from such tools, to the detriment of our workflow.

### End-User Requirements

The End-Users are the other people in my small game development studio. Communications between our roles has been problematic in the past so sharing tasks with the rest of the team needs to be easy. We need specialised sections for programming, 3d design, animation, and writing. One of our end users speaks korean as his first language, so we need translations for the site interface.

### Proposed Solution

The site will let you organize tasks into super-categories, which will be editable but as a basic idea would be split up as General, Animation, 3D, Writing, Programming. Inside a super-category will be a collection of creatable, editable, and moveable categories, which you can insert tasks into. A task has a basic description and contains sub-tasks which will be the actual things you are checking off. Users will be able to archive and delete cards they have completed.

By organising the website like this, it allows each part of the game development pipeline to remain separated into its specific section, while also allowing users to get quick feedback on a task through an alert system despite each section being split up like this. 

-------------------------------------------------

## Relevant Implications

### Usability Implications

_Usability_ relates to how _easy an interface is to use_ for the end-users. Interfaces should follow common _usability heuristics_ and make intuitive sense. An interface should be usable to the end user without any help or assistance, and ensure it is quick and simple to use the website for their needs. Neilsen's 10 usability heuristics are the following:

1. Visibility of system status - Users are informed about what is currently happening. Feedback needs to be given to know the interface is performing an action, through status messages and progress bars.
2. Match between system and the real world - Words, images and concepts should be familiar to the user, and reflect real world experiences, for example, a shopping website will use a trolley icon for your cart as it communicates what its function is without the user having to check it themselves.   
3. User control and freedom - Users need to have control over the interface, they should be able to cancel or undo actions to exit unwanted states.   
4. Consistency and standards - The UI of an interface should follow the conventions of other similar interfaces, as the user will already be familiar with how to navigate it. For a website, placing menus along the top and/or left of the screen and using standard icons (house for home) is best practice.  
5. Error prevention - Prevent users from making errors when possible, for irreversible actions, make the user confirm what they are doing. For forms, include sanity checks so that information entered follows the correct format and is believable.   
6. Recognition rather than recall - Make objects, actions, and options visible and recognisable. Users should not have to remember information from one step to the next.  
7. Flexibility and efficiency of use - New users should be able to easily use the interface, but more experienced users should also be able to speed up their workflow, for example, by learning keybindings for actions rather than navigating menus.  
8. Aesthetic and minimalist design - Keep the interface as simple as possible, and focus on the current user action. only show necessary information at any point and avoid overwhelming the user with pointless information.  
9. Help users recognize, diagnose, and recover from errors - Error messages should be plain and helpful, ideally suggesting a solution to the problem as well. 
10. Help and documentation - Try to create a system that doesn't need documentation, but if needed is easily accessible and easy to understand.

Usability is important to my digital outcome as it needs to be easier to use for our needs than competing tools such as trello. By creating a specialised site for my end users I hope to create a fast and effective tool that will aid in our workflow and allow us to get more done as a team. If the site is not easy or intuitive to use then it will go unused and any time migrating current tasks over will have been wasted. Because of this I need to ensure that it fits the needs of all our team members and is still quick and simple to use.

I need to make sure each part of my digital outcome follows Neilsen's usability heuristics. The most important heuristics to consider for my team are Flexibility and efficient of use, and user control and freedom. Aesthetics and minimalist design is also quite important to this project as my team would prefer an interface that is very easy to understand and not overwhelming. To do this I will follow pre-existing design conventions to make the interface as familiar as possible, implement an undo for most actions, and keep the website minimal. The only page with lots of information will be the task browser, where tasks will be organised into categories and easy to find.

### End-User Implications

The _End-User_ implications of a digital outcome are connected to the _specific needs_ of the user. A digital outcome needs to adjust its design and functionality to be as usable as possible for its target audience. This can include targetting language toward the target audiences comprehension level, and making sure your outcome functions on the type(s) of devices most likely to be used by your end users.

End-User implications are relevant to my digital outcome as it is required to be easier to use for our specific needs within game development compared to competitors like Trello who have more general end user considerations. If the project doesn't meet our specific needs it will be no better than just using Trello.

My project aims to cater to our specific needs by giving more separation between different aspects of game development, by organising each aspect into its own super-category. This allows multiple different categories of task to be made inside each super-category which will make managing tasks for each team easier. Additionally, to address potential concerns over communication between teams with this approach, I will include an alert feature, which allows a notification to be made on a task so people on different teams can review it and give their input without needing to organise that through another medium. I also need to consider that one of our team members speaks korean as their first language, so I can cater to their end user needs by including korean translations for the site menus and navigation.

### Functionality Implications

The _functionality_ implications of a digital outcome relate to how _well it works_ for the end-user, in terms of meeting its intended purpose. A functional outcome will _do everything it is supposed to_ and _meet its purpose_, be _bug free_ and work without headache, and _work as expected_ from the users point of view.

Functionality implications are relevant to my digital outcome as the website needs to function for us to use it. If the final product is riddled with bugs that require careful action to work around or doesn't work entirely, then it will not be usable. 

When creating my outcome, I need to address everything I initially promised before adding any extra features, to ensure that my outcome is complete and functioning with all the features promised to my end users by the deadline, as otherwise the outcome will fail. I should also take into consideration input validation for the forms themselves, though due to the private nature of the website I should not need to implement many server-sided input validation checks.

### Aesthetic Implications

The _aesthetic_ implications of a digital outcome relate to _how it looks_ in terms of design. An outcome's design should _be appealing_ to its end-users, _follow conventions_, and make good use of _colour_, _font_, and _positioning_. This implication largely determines the overall user-experience of the outcome and a great deal of thought should be put into it.

Aesthetic implications are relevant to my digital outcome as the interface needs to look good otherwise my end users _WILL_ complain. I want to ensure the best experience for my team in order to improve our workflow while developing games, and that means putting thought into the aesthetic implications of my digital outcome.

I need to gather constructive input from my end users on the various design prototypes created of my interface to make sure everyone is happy with it. I should seek input on any further design changes or additions later down the line as well.

### Accessibility implications

The _accessibility_ implications of a digital outcome involve ensuring it is _available to_, and can be used by, _all end-users_, regardless of their abilities.

Accessibility implications are relevant to my digital outcome as I myself am visually impaired, and one of our end users suffers from colour blindness. Type of device doesn't matter as it will only be accessed on desktops or laptops.

I should avoid using colour combinations that are hard to differentiate for our member with colour blindness (should ask them specifically what type of colour blindness they have). I should make use of contrast to make it easier to read for the visually impaired. The main colour theme will likely end up being light or dark greyish-blue, as AMOLED or similar themes are hard to read for me due to my astigmatism causing halation. Dark themes are still usable for me when the background is not completely black. I should make sure the interface works on common laptop aspect ratios, as well as the common 16:9 aspect ratio. 

-------------------------------------------------

## Final System Design

### Database Structure

![Db schema](images/dbV2.png)
<!-- Place a image here that shows the *final design* of your database: tables, fields and relationships. -->

### User Interface Design

![Final figma design](images/finalDesign.png)
![forms in figma](images/v1.0.0%20form.png)
<!-- Place images here that show your *final design* of your UI: layout, colours, etc. -->


-------------------------------------------------

## Completed System

### Database Structure

![production schema](images/productionSchema.png)

Further documentation for the database can be found in the [Database.md](database/Database.md) file.
<!-- Place a image here that shows the *actual database structure* that you implemented: tables, fields and relationships. -->

### User Interface Design

![](images/Main.png)
Not shown here is tasks that are fully completed have a blue border, and the percentage reflects the percentage of subtasks within that are completed.

![](images/Supercategory.png)

![](images/Category.png)

![](images/Task.png)

![](images/Subtask.png)
Chromium browsers have a time selector for the datetime picker, while other on other browsers you have to input the hours and minutes manually

![](images/CompletionTest1.png)
Not shown here are tasks that are nearly due will show a red border and due date text
<!-- Place screenshots and notes here that show your *actual system UI* in action. -->


-------------------------------------------------

## Review and Evaluation

### Meeting the Needs of the Users

This was by all metrics, an ambitious project. Not every end user goal was met, but most were to an extent I am happy with. My end users were the people in my studio, and the problem I set out to adress with this project was to improve communication between us. Another of my end user requirements at the start was to include korean translations, though the member of our team who speaks Korean has not been active for quite some time. Seeing as the only things I could provide translations for would be the some of the user interface things (for example, Archive, Supercategories, New Task, ect) I decided to drop this feature. Apart from that, the site is able to suit the needs of my end users, allowing us to organize tasks effectivley.

### Meeting the System Requirements

All features apart from korean translations and the alert system were implmented into the final product. The alert system was canned because it was unplanned and I realised it would not be as useful as I thought while designing the feature at the start of this project. The drag and drop system worked really well for the supercategories and categories, but not the best for categories. If I did this again, I would try and do something similar to trello where instead of switching the dragged element with the element you drop onto, trello inserts the dragged element next to the element you drop onto. My end users also asked me to add subtask reordering, and checklist subtasks, however a combination of me deciding those wouldn't really make sense and then forgetting about this feature request until the 21st of august led this to not getting done. Subtasks were supposed to effectivley be checklists, and while reordering them would have been a nice feature, I think it works well enough like this.

### Review of Functionality

While the site has more bugs than I would like, I would argue it is still functional enough to be useable. The only major bugs that did not get corrected were the buggy task reordering, which is not something you have to do all the time, and you can work around it fine by refreshing. The rest of the functionality was addressed in the [development log](Development.md#210824-functionality-testing) (24/08/24 functionality testing section)
Voxsinity also asked for some kind of markdown or rich text support for the task descriptions and subtasks, though 

### Review of Accessibility

Accessibility was addressed by designing the website with low vision in mind, as well as [asking my colourblind end user for feedback](Development.md#color-palette-suggestions). There we decided to make the green success accent into the main blue accent, as it is easier for him to make out from the red negative accent.

### Review of Usability

Many features were included to follow Neilsen's usability heuristics. For example there are many minimalist techniques used to communicate information in a non-intrusive manner, such as showing a blue border around the percentage box on completed tasks. This is relevant to both asthetics and minimalist design and recognition rather than recall, as the blue accent is more recognisable as the 100%

![](images/completed.png)

This blue border makes it easier to recognize that the task is done, without having to read the 100% over every task, which can become overwhelming on pages with many tasks and categories. This lets you unconsciously filter out the completed tasks when searching for a specific one.

This technique is also used for the borders of subtasks, to draw the user to subtasks that are soon due and away from subtasks they have completed already.

![](images/Deadline.png)

User control and freedom was also a addressed with this site, the user is prompted with a popup before deleting anything to make sure it wasn't accidental, and they can cancel the action.

![](images/popup.png)

## Review of Asthetics

A great deal of effort was put into making the website look nice. Most of the improvements made between me and my end users were to do with the look of the site, such as settling for the blue accent [here](Development.md#color-palette-suggestions). Lots of the [early development](Development.md#270624) was done on getting the left side supercategory selector to work, as I believe it was one of the most important asthetic elements of the website, and I had to get the layout somewhat how I wanted it to test the javascript heavy elements, like the drag and drop. 

## Review of Accessibility

To meet the Accessibilty implications, I set out for with this project by negociating with my end user with colour blindness to make the website more user friendly to him [here](Development.md#color-palette-suggestions)

![](images/Deadline.png)
This is where I addressed the color blindness issue in my final product, by making the completed accent the blue site accent, instead of green, which is hard for him to tell apart from red.

I can also say that the website is decently readable to me without my glasses on, and feels perfect with them on. The way this was achieved was by using large text where possible. The monospace font for the user interface makes it easy to read. The sans serrif font used throughout tasks is a little harder to read but