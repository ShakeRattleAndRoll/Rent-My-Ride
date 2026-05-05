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

## PARTIALLY DONE

### FEATURES
* Email Verification
* View Details in My Rentals
* (Search bar in messages, searching for users in the system) -- tanggala ra ug dili nata ani
* Not allowing block user to check your car and profile
