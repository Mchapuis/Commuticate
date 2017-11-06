# Job Search
Yhack 2016 - web service using GoogleMap API to locate job postings from Indeed.com


## The recent update is not working yet, the version in the "old" folder is but using ArgGis API

#### IMPORTANT  
The file doesn't display all the searches from indeed.  
To see what queries are imported:  

1- open the debug in the browser  
2- You should see Objects...they are JSON objects from an Ajax call of the Indeed API  
3- Open the first Object, under Results you will see an array of Objects containing information about the query.  
4- example of the log for a search of Austin and waiter. We would use the latitude and longitude numbers to add markers to the map.  

