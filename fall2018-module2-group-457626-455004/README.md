# README #

### What is this repository for? ###

* Module 2 File Upload Site

### How do I get set up? ###

* Go to the link below
* http://ec2-18-222-123-63.us-east-2.compute.amazonaws.com/~kendallpomerleau/Module2/login.html

### How do I operate the application? ###

1. Login with a username on file. (see section 'Usernames on File')
2. Options:
    * Enter a file name to view or delete (list of file names at the top of the page).
    * Upload a new file from your computer by choosing a file and then clicking upload.
    * Type in the URL of a file to upload a file from the internet.
    * Select a file and enter a username to share with that user.
3. To perform another operation, click the back button.
4. To logout, click logout. 

### Usernames on File ###
* kendallpomerleau
* sarahchitty
* codingisfun

### Creative Portion ###
For our creative portion, we implemented file uploads from a URL and a way to share your files with other users. 

For file uploads from a URL, the user enters a URL into the textbox provided. From there, our code checks to make sure the URL is valid. It gets the contents of the file from the URL and puts that into a path assocoated with the users' uploads. This file can then be viewed just like files uploaded directly from a computer (only if the file uploaded through the URL is less than 2 MB).

For sharing a file with other users, the user selects a file from their computer. This must be a file that has already been uploaded onto the site for the current user. The user then can enter a username to share this file with. This username must be valid, and cannot be the current username. Once clicking "share with other user," the file will show up as an available file for the other user. To access it, the current user must logout and login again as the other user.

### Creators ###

* Kendall Pomerleau and Sarah Chitty