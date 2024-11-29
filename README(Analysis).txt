
What Makes the code Amazing 

-> Use of Eloquent Models make it very easy to maintain and read
-> Use of Repository pattern makes it very clear the purpose (That Model related code is in the repository and the logic is 
in the Controllers).



What Makes this code Terrible In BookingController 
-> No Standard Format for sending response data and documenting the response. 
-> Use env() rather than using proper config constants 
-> No error handling in either controller 
-> Request data validation should be in the separate class (request classes)
-> No proper use of constants 
-> Tightly coupled to BookingRepository ****************


How to make it better 
-> defined separate constants in config file 
-> changing $repository to bookingRepository for better readability
-> adding proper error handling 
-> Defined Response functions in controller so that there is standard followed accross all the controllers 
-> added helper methods for reusebility 

