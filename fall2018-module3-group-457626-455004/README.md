# README #

### What is this repository for? ###

* Module 3 News Site

### How do I get set up? ###

* Go to the link below
* http://ec2-18-222-123-63.us-east-2.compute.amazonaws.com/~kendallpomerleau/Module3/login.php

### How do I operate the application? ###

1. Login with a username and password on file (see section 'Usernames and Passwords on File'), register as a new user, or login as a guest.
2. Options For Guest Users:
    * Browse articles by clicking on article name (link to website).
    * These users can also view comments and likes from registered users but cannot like/dislike an article, make a comment, or edit anything themselves.
3. News Page Options for Registered Users:
    * Browse articles by clicking on article name (link to website).
    * Like or dislike an article.
    * Comment on an article by clicking comment button and then inputting the article name and your comment.
    * Share via email or Facebook.
    * Go to your profile page.
4. Profile Page Options:
    * View your uploaded articles and either delete or edit your story.
    * View your comments on articles and either delete or edit your comments.
    * Upload an article by inputting the URL, article name, and a short blurb under 500 characters.
    * Go back to News Page.
5. To logout, click logout. 

### Usernames and Passwords on File ###
* Username: coffeelover     Password: ilovecoffee
* Username: doglover        Password: ilovedogs

### Creative Portion ###
For our creative portion, we implemented a profile page for registered users, the option to share via email or Facebook, and the ability to like/dislike an article.

A registered user has additional abilities on their profile page. This is where they can only view their own uploaded articles and comments. This benefits the user because it allows them to easily access their own articles/comments without parsing through the general news website. It also makes the news site more personal for the users. To implement this, we made separate database tables for each username when they register so we can easily store and access each user's personal data (including their uploaded articles, liked articles, disliked articles, commented articles).

The option to share via email and Facebook is implemented by buttons beside each article that redirect to email or Facebook. We used html's "mailto" attribute for email, and Facebook's sharing platform for external articles. This functionality integrates our news site with more popular forms of communication/social media and enhances sharing efficiency.

Registered users also have the ability to like or dislike an article. This is useful so that other users can see which articles are popular, including guests. To implement this, we store likes in a separate database tables with the article and its number of likes. We then access this table by article name to display and change the number of likes based on registered user input. 


### Creators ###

* Kendall Pomerleau and Sarah Chitty