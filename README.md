# RENT MY RIDE

### SETUP FOR SEEDERS 
#### copy the images inside of TestCarImage in resources, then go to storage/app/public if you dont have \[car_photos\] folder inside, then create new one same folder name \[car_image\], then paste the images inside of car_image, when running the seeder \[php artisan migrate:fresh --seed\]


## ALREADY FINISH

### UI 
 * LOGIN
 * POST A CAR
 * PROFILE
 * AVAILABLE CAR
 * MESSAGE 
 * GARAGE: 
    * MY LISTING 
    * MY RENTAL
* HOMEPAGE

### FEATURE
* Login
* Register
* Posting a car
* Logout
* Sign up -> Homepage
* Save change in edit profile
* Clicking the add to cart it saves in garage my cart

#### New Added Features and UI Changes 4/30/2026
* No loading when going to another section of website
* Allow change of profile picture
* Allow cancel of pending rental
* Allow edit and delete of car
* Allow to show details of a car 
* Allow owner to accept customer who want to rent a car
* Nav bar now shows profile picture
* The emoji when no result like listing or rental can now be clickable
* Profile stats now work
* Slight change in available cars UI
* Navigation Responsive

#### UI Revamp and Fix Bug Features 5/2/2026
* Message now works
* Profile and edit profile UI Changes
* Available Cars details 
* My Cart "Rent Now" Done

#### Added QOL Features and Bug Fixes 5/3/2026
* Car wont show in available cars if its occupied
* if car is in cart and its occupied it have visual presentation that its occupied
* Added you cannot add to cart if you already pending a request
* Fix that you can add to cart even you already have it in cart (cause duplication)
* You can click profile of owner in the car details
* Search filter now works 
* result counter is now accurate
* Added Search Filter for Price, Transmission, and FuelType

#### Added Something & Bug Fixes 5/4/2026
* Added database seeders
* Fixed when editing the post the date is default resulting in mm/dd/yy not the date when the car is owned
* Editing posted cars 

#### Added Features & Bug Fixes 5/5/2026
* Added a pagination (max of 20 cars per page)
* Added a jump to page
* Added a view profile, mute user, and block user in message clickable by clicking the three dot next to profile
* Update the search filter, added model and brand
* Fix the bug when clicking the search it immediately show the filter
* Fix the bug when clicking the filter it also show you your search suggestion overlapping the filter

#### Added Features & Bug Fixes 5/7/2026
* View Details in My Rentals
* "First come, First serve", Longest days, and Shortest days options on pre order added
* Search bar in messages, searching for users in the system
* Display in the Garage/My Listing and Messages number notifs

#### Added Features & Bug Fixes 5/8/2026
* No reload or full refresh for most of the clickable 
* Show real time update (e.g notif) no refresh needed
* Optimize the video in homepage
* Livewire Messages
* Delete Post
* Search bar in messages, searching for users in the system optimization
* Allowing block user to check your car and profile

#### Added Features & Bug Fixes 5/9/2026
* Homepage redesign
* Cart rent now bug fixed

#### Added Features & Bug Fixes 5/10/2026
* Customer rental logic update
* Auto Accept Feature in my listing
* Notification Feature (left side next to profile pic)
* Modal for cancel pending
* Occupied cars not shown in available cars
* Show Occupied indicator if checking owner profile

#### Added Features & Bug Fixes 5/11/2026
* Delete modal of notification
* Post a car & Edit post UI Changes
* You can now see what your available cars look now when posting a car and editing
* Log in and Sign up UI improvements
* Landing page 
* Authorization:
    * Email Verification
    * "Forgot Password" feature

#### Added Features & Bug Fixes 5/13/2026
* Separate the history of rented cars
* Message not appearing after action fixed
* Available Page, My Listings, My Rentals and My Cart
        * Details (Listings and Rentals)
        * Cards improvement
* Messages UI color update
* Messages responsive

#### Added Features & Bug Fixes 5/15/2026
* Added admin panel
* Added an option to show car optionally in my listing
* Fix when clicking certain action it fully reload
* Make notification dont need to fully reload to see new content
* Fix ayto accept nearest start day not working
* Auto accept in my listing UI change
* Fix the bug when doing certain action and clicking the back, the action you did is redo
* UI changes
* Livewire modals
* Pre order conflict schedule fixed
* Notification open bug fixed

## PARTIALLY DONE

### FEATURES
* Homepage backgroung with responsive
   
