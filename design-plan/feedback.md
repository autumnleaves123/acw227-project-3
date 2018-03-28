# Project 3 - Design & Plan

Your Name: Michael Choe, mjc463

> Hello. Please read through the comments we have left on your design plan and make sure to make
changes to your design plan and website based on the feedback we give you.
> Comments are listed inside block quotes like this, so make sure to look carefully!
> If a section in your design plan doesn’t have block quotes, then you’re good to go for that
section! If there are block quotes, there’s a good chance you’ve missed something crucial.

## 1. Persona

I've selected **[Abby/Patricia/Patrick/Tim]** as my persona.
> You do not need this line in the final submission ^

I chose Tim as my persona for a few reasons. It says that he likes to learn all the available functionality on all of his devices so i think he would have a good time discovering all the things he can do with the photo gallery. He also doesn't mind taking risks with his technology so things like adding/deleting tags could freak out different people but for him it would be a fun explorative learning process. I think that when dealing with logging in, searching through tags, adding tags, deleting tags, and uploading photos someone who is more adventurous and has a stronger understanding of technology will have a better time using the platform.

## 2. Sketches & Wireframes

### Sketches

[Insert your sketches here.]
![](sketch.jpeg)

### Wirefames

[Insert your wireframes here.]
![](wireframe1.jpeg)
![](wireframe2.jpeg)
![](uploadpage.jpeg)

I think that my design organizes all the possible functions of the website very well. You can log in/log out, search through tags,  delete/add tags and then upload/delete photos. These are a lot of features and laying it out for Tim would be a great foundation for him to explore the website. Even if he is an adventurous guy and confident in his adeptness with technology, it's still important to make the information clear to him. As said in the persona, if something doesn't work he's just going to blame the technology and not himself so I wanted to make sure that the layout is appealing and easy to navigate

> It is a little hard to understand what your wireframes are trying to show. More notes/comments on each wireframe itself would be really helpful. In addition, more notes regarding the margin/sizing/spacing values for the elements on the page would be nice to see.

## 3. Database Schema Plan

[Describe the structure of your database. You may use words or a picture. A bulleted list is probably the simplest way to do this.]

Table: IMAGES
* field 1: image id (numerical, unique, accumulates)
* field 2: image files (text)

table: tags
* field 1: tag (text, unique)
* field 2: image that is correlated to the tag (Text)

Table: User
* field 1: session id (numerical, unique)
* field 2: user ID (text, unique)
* field 3: password (text)

> Right now your database schema does not incorporate a many-to-many relationship between images and tags and it does not have maintain any notion of which user submitted a particular image (which you need because users are only supposed to be able to delete images they submitted). At a bare minimum, your schema should look like the following:  
* images(id, user_id)  
* tags(id, name)  
* image_tags(id, image_id, tag_id)  
* users(id, username, password, session)  
If any of these tables do not make sense, please come to office hours.

## 4. Database Query Plan

1. All photos
select all records and all fields from images table

2. Search through images using a tag
input is tag and the search will see if any images have that tag through the tags table

3. insert image
insert image with accumulative photo id (so its unique) and then tags are optional, file is a must

4. delete image
will remove the image from the images table

5. insert tag
if it is not already there add tag to the tags table and then put the image correlated in the second field. if tag already exists but you are adding it to the image then just add the image to the existing tag's second field.

6. deleting tag:
remove tag from the tags table and also the images its correlated with so it disappears from the images as well.

4. logging in
user puts in id and password and if it matches an entry (password and id must be of same entry) then the user will be logged in and then the empty field for session id will have a unique id

5. logging Out
user clicks log out then it will terminate the session and delete the session id that was made when logging in

> For future submissions, please actually write out SQL code for the queries, it will help you later on and it will help us catch any mistakes beforehand.

## 5. Structure and Pseudocode

### Structure

[List the PHP files you will have. You will probably want to do this with a bulleted list.]

* index.php: here you can see the gallery but you are not logged in
* loggedin.php: if you are logged in you see the same information but extra features like delete image since you are logged in
* upload.php: upload page to upload an image
* includes/navigation.php: keep navigation consistent throughout pages

### Pseudocode


#### index.php

```
Pseudocode for index.php...

include navigation.php

make a div containing all the photos
  make a div containing a photo and its relevant tags
    the image
    the Tags
    "view larger" (to go to single image)

and then based on if its a single image or like a searched tag then the images shown will change dynamically
if its on the searched tags then it will look just like the pseudocode above just that the images with those tags will show (similar to the search function in project 2)

if its a single image then

make a div containing all the photos
  make a div containing a photo and its relevant tags
    the image
    the Tags
    "view larger" (to go to single image)
    delete tag (if you're logged in)
    add tag
    delete photo (if you're logged in)
    go back (to the original page showing ALL the photos)

#### includes/init.php


psuedocode for upload.PHP

div containing everything
  p tag "UPLOAD!!"
  form with two inputs
  file Name
  optional Tags
  button: upload!
```
messages = array to store messages for user (you may remove this)

// DB helper functions (you do not need to write this out since they are provided.)

db = connect to db

...

```

#### TODO

select all images from image table
select all tags from tag table

if the user is logged in
get that session id for them

search form:
if text input matches any tags in tags table, select then show the images that are correlated with that tag, hide all others

add tags:
if the tag does not exist:
  add it to the table and the image you trying to add it to
if the tag does exist:
  do not add it to the table, just add the image you are trying to add it to to the tag's correlated images fields

delete tags:
delete tag from table, delete all correlated images so that it won't show on the image anymore

delete image:
delete record from the images table and from all the tags its associated with

upload image:
add new id to the table, add new image file and then if the user wanted to put in tags then they can, but this is optional

```

## 6. Seed Data - Username & Passwords

[List the usernames and passwords for your users]

* user1 : sallylee20
* password1: ilovedogs20

* user2: photolover1000
* password2: iuseanikoneveryday1000
