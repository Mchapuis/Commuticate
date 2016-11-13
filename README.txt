# Commuticate
Yhack 2016 - web application using ArcGis api to locate job postings from indeed

Open the file on a server to run it

#IMPORTANT
The file doesn't display all the searches from indeed.
To see what queries are imported:

1- open the debug in the browser
2- You should see Objects...they are JSON objects from an Ajax call of the Indeed API
3- Open the first Object, under Results you will see an array of Objects containing information about the query.
4- example of the log for a search of Austin and waiter. We would use the latitude and longitude numbers to add markers to the map.

EXAMPLE OF LOG IN BROWSER:

Object
dupefilter
:
true
end
:
25
highlight
:
true
location
:
"austin"
pageNumber
:
0
paginationPayload
:
""
query
:
"waiter"
radius
:
30
results
:
Array[25]
0
:
Object
city
:
"Austin"
company
:
"Firehouse Subs W. William Cannon"
country
:
"US"
date
:
"Thu, 03 Nov 2016 12:57:02 GMT"
expired
:
false
formattedLocation
:
"Austin, TX"
formattedLocationFull
:
"Austin, TX"
formattedRelativeTime
:
"10 days ago"
indeedApply
:
true
jobkey
:
"752573b455eb6447"
jobtitle
:
"Team Member/Cashier (flexible schedule) South Austin"
latitude
:
30.266483
longitude
:
-97.74176
onmousedown
:
"indeed_clk(this,'534');"
snippet
:
"If you are looking for server, wait staff, <b>waiter</b>, or other FOH opportunities please apply for this wonderful opportunity...."
source
:
"Indeed"
sponsored
:
false
state
:
"TX"
