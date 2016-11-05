# The Beauty Salon Theme Documentation

The Beauty Salon theme is a theme created by [Red Factory](http://redfactory.nl/themes/). 
It has a powerful framework which allows you to create the website you'd like
to have with as few restrictions as possible.

If you have any questions or comments please feel free to use our [support
forum](http://www.redfactory.nl/themes/forum).

# Installation

## Automatic Installation

1. Log in to your WordPress admin and go to the 'Appearance' page. Click on
   the 'Install Themes' tab on top. Click on 'Upload' below the tabs.
   
2. Upload the theme package and click 'Install Now'

3. Once the installation is complete click 'activate' to activate the theme


## Manual Installation 

1. Upload 'The Beauty Salon.zip' to your WordPress themes directory. This is usually 
   found in 'wp-content/themes/'

2. Extract the files. This can usually be done through the file manager in 
   your cPanel. If you have no idea what this is or you don't have cPanel
   access, you will need to extract the files on your hard drive and upload
   all the files instead of the zip package.

3. Log in to your WordPress admin and go to the 'Appearance' page. You should
   see the theme in the list. Click on 'activate' to activate the theme. 
   
   
# About Red Factory Themes 

Red Factory themes use a common theme framework. This includes shortcodes,
widgets, custom field handling, the control panel, and so on. To make sure
you get access to all the new features we have implemented a framework 
upgrade option. 

Note that the framework update will only update your framework, it will not
update your actual theme. Many people make slight modifications to theme
files and updating the theme itself automatically would remove these 
modifications. 

The framework update will remove all files in your theme's framework
folder and add all the files from the new package downloaded. It might also
modify the main readme.txt in the theme directory. 

# Theme Usage 

Your theme includes a lot of options and settings to modify. We recommend 
getting to know your theme first before you implement it on a live site. 
Playing around with options is encouraged, take a look at what everything 
does, look at the information next to options, sometimes they contain
tutorial videos as well. 

We have these tutorials available at http://redfactory.nl/themes/tutorials/
which also includes a videos section to help guide you through your theme's 
options and settings. If this is your first time using a theme with many 
options or you just want a quick rundown, feel free to visit the links 
mentioned or read the documentation below. If you have any questions, feel
free to use our support forum at http://redfactory.nl/themes/forum

# Theme Options and Post/Page Options

Your theme has two distinct group of options. One group - Theme Options - 
handle the global settings for your website. This group can be found under
'Appearance->Theme Settings' in the admin. Site logo, background color,
default sidebar position and so on can be set up, and will be used on all
pages. 

The second group - Post/Page Options - are related to one specific page
on your site. This option group can be found on individual edit pages is 
the admin, under the editor. Using this group you can control weather 
the title, featured image, etc. should be shown for that particular 
post/page only. In many cases the global options can be overwritten as well. 
If you have set the sidebar to be on the left in the Theme Options but you
want it to be on the right for one post/page you can do so in the Post/Page
settings

Both your Theme Options and Post/Page Options are split into option groups
for easier use. These groups are set up in a tabbed are and only the 
relevant options will be shown to you.

# Options Types

There are a few types of options available such as text inputs, select
boxes, radio buttons, etc. Some require some special actions to be taken,
please read the section below if you are having trouble. 

## Text Inputs 

These elements require you to enter some text into them. Just type away 
and save, that's it. 


## Textareas 

Textareas are just larger text inputs which allow you to add some longer
text. 


## Select Dropdown 

Select dropdowns are simple select boxes, click on them to show the items 
and select the one you want. 


## Multiselect Dropdown 

These special dropdowns allow you to select multiple items. Click the box
to expand it, select an item and it will show up in a list under the box. 
To choose another item, expand the dropdown again and select something else.
It will also show up in the list below the box. 

You can reorder the list create by dragging and dropping items and you can 
remove items from the list by clicking 'remove'. Don't forget to save your 
settings even if you just reordered the items. 


## Upload Input 

Upload inputs are text input boxes which have a browse button to their 
right. These act as regular inputs, you can paste the URL of a file into
them. However, by clicking the browse button you can also upload a file 
and use the URL of the newly uploaded file in the input as well. 

To do so, click the upload button and click 'select files', or drag and
drop the file into the indicated area. Once done, click 'insert into post'
in the form that appears to insert the URL into the input field.


## Color Selectors 

Color selectors are text input boxes with a color swatch next to them. 
If you click on the text field a color picker will appear, allowing you
to choose any color you like. You can also simply paste a HEX color into
the field yourself.

# Theme Options in Detail 

Below is a list of all the options this theme supports and their short 
description. Feel free to experiment and use the forum if you run into any
problems or have any suggestions. 


## Website Logo 

This option enables you to specify a logo for the website. Once uploaded and
save the logo will appear in the top left of the header. Be sure to upload 
an appropriately sized image as it will not get resized, it will be shown
in the exact size uploaded. 


## Favicon 

A favicon is an icon associated with your site. It shows up in browser tabs,
favorites and in various other places sometimes. This should be a 16x16
pixel .ico file. If you don't have an .ico file handy you can generate one
from a regular image at http://favicon.htmlkit.com/favicon/


## Apple Touch Icon 

An apple touch icon is an icon associated with your website used on Apple 
devices. This is the icon which would show up on the iPhone home screen
if your website is added there. 

This icon should be 114x114 for optimal appearance on all devices. To learn
more about these icons, visit http://goo.gl/ye8QM (Apple Developer Website)


## Sidebar Position

The position of your main sidebar can be set up here. You can override this 
setting on individual pages and the sidebar can be disabled for individual
pages as well. 


## Custom Sidebars

By default your website has one sidebar called 'Sidebar'. However, you might
want to show different items in the sidebar on your about page. To do this
you can create a new sidebar here, and then add items to it in 
'Appearance->Sidebars'. 

To create a new sidebar just enter its name into the text field. If you 
want to create more than one sidebar, separate the names with a comma. 


## Read More Text 

In various places throughout your site you can click a link or a button to
'read more'. You can change the text of these links to whatever you want
using this text field. 


## 404 Error Title 

If a user clicks a broken link or visits a non-existent page on your site
through other means a 404 error is shown. You can customize the title of 
this page using this option. 


## 404 Error Message 

If a user clicks a broken link or visits a non-existent page on your site
through other means a 404 error is shown. You can customize the message
shown on this page (under the title) using this textarea. 


## No Search Results Title 

If a user searches for something which yields no results a 'no results' 
section is shown. You can customize the title of this section using this
input field. 


## 404 Error Message 

If a user searches for something which yields no results a 'no results' 
section is shown. You can customize the message in this section (shown
under the title) using this textarea.  


## Password Protected Post Message 

In the admin you can set a password for a post if you want only people with
the password to be able to view it. In this case, before entering the 
password, users are shown a form which has a message on top. You can 
customize this message using this option. 


## No Posts Error Title 

In some cases there will be no posts available on a specific page. A good
example of this is your home page if you have no posts at all. You could 
also create a gallery by selecting some categories, but if there are no
posts in the categories, the page can't show any posts. These are cases 
where the 'no posts' error is shown. It includes a title and a message,
the title can be modified using this option. 


## No Posts Error Message

In some cases there will be no posts available on a specific page. A good
example of this is your home page if you have no posts at all. You could 
also create a gallery by selecting some categories, but if there are no
posts in the categories, the page can't show any posts. These are cases 
where the 'no posts' error is shown. It includes a title and a message,
the message can be modified using this option. 


## Analytics Code 

If you use any website tracking service you can paste the code you need
to add to your site in here and it will be added to your website.  


## Primary Website Color 

Your website's primary color shows up in a few places. Default buttons, 
link colors current navigation item markers and so on. If you feel you've
changed the color and can't find any good ones feel free to leave it blank 
and it will pop back to the default blue


## Background Color 

You can set the background color for your site with this control. We 
recommend that the background color and the primary color of your website
to have a good contrast ratio (dark background light primary color or vice
versa).


## Background Image 

If you would rather have an image or a pattern as a background you can 
upload one here. If you want a simple subtle striped background there are
a number of online tools you can use to generate one, like
http://www.stripegenerator.com/


## Heading Font 

The heading font of your website can be set to any font you wish by typing
its name into the text box. We recommend using either web-safe fonts or 
fonts from the [Google Fonts library](http://www.google.com/webfonts).

If you would like to use a font from the Google font library, just find its 
name and type it in the box. The code needed to implement the font will be 
added to the website automatically. 

You can also use font stacks by typing in multiple font names, separating
them with a comma. 

## Body Font 

The body font of your website can be set to any font you wish by typing
its name into the text box. We recommend using either web-safe fonts or 
fonts from the [Google Fonts library](http://www.google.com/webfonts).

If you would like to use a font from the Google font library, just find its 
name and type it in the box. The code needed to implement the font will be 
added to the website automatically. 

You can also use font stacks by typing in multiple font names, separating
them with a comma. 

# Page/Post Options 

Each page or post on your website has separate options attached to it. Some
options such as the 'Page Structure' settings are present for all posts. 
There are some settings that are only available in special circumstances. 
Settings for galleries will only be visible on gallery pages, post list
settings will only be seen on post list pages, and so on. 

All the options available for your website are shown below, categorized
into the groups they respectably appear in. 

# Page Structure  

This tab is present on all single posts. It allows you to control the 
structure of the whole page the post is shown on. 


## Sidebar Position 

The position of the sidebar by default is always set in the Theme Settings. 
If you want to override the default setting you can do so here.


## Custom Sidebar 

If you've created multiple sidebars you can choose which one to show on 
each page/post. By default the sidebar name 'Sidebar' will be shown. 


## Disable Sidebar 

This checkbox allows you to disable the sidebar on the page/post in question
completely. 

# Post Content  

This tab is present on posts and pages and enables you to modify the content
that is shown in the post/page. 


## Remove Post Meta 

If checked the post meta area will not be shown on this post page. The 
post meta area shows the author, date, tags and categories.


## Remove Featured Image 

If you set a featured image in a post that image is shown on the single post
page. You can choose to remove it if you wish with this checkbox.


## Remove Title 

In some cases you may want to disable the title on some posts. You can do
so with this checkbox. 

# Post List Settings  

This tab is present on pages where the page uses the 'Post List' template. 
It allows you to set up which posts are shown inside the post list and 
gives you control over some additional settings. 


## Categories 

Allows you to select a number of categories to include or exclude. The 
categories currently selected will be visible under the select box. 


## Exclude or Include 

Select weather you want to include or exclude the selected categories. 


## Disable Page Title 

The title of the actual post list page can be shown above the list of
posts. If you choose to, you can disable this. 


## Show Page Content 

If you want to show additional details about the post list, you can add 
content to the post list page itself. This can be shown by checking this
box.  

# Gallery Settings  

Galleries give you a great way of showing off images. You can include or
exclude posts to use the featured images from and you can set up numerous
display options as well. 


## Categories 

Allows you to select a number of categories to include or exclude. The 
categories currently selected will be visible under the select box. 


## Exclude or Include 

Select weather you want to include or exclude the selected categories. 


## Disable Gallery Title 

The title of the actual gallery page can be shown above the gallery. 
If you choose to, you can disable this. 


## Show Gallery Content 

If you want to show additional details about the gallery, you can add 
content to the gallery page itself. This can be shown by checking this
box.  


## Gallery Columns 

The number of columns to use to hold the gallery images can be set up here. 
Images will scale to fit so you don't have to worry about uploading exact
size images. 


## Disable Featured Image 

Each gallery item consists of the featured image, the title, the date,
the content and the read more link. The featured image can be disabled
by checking this box. 


## Disable Post Titles 

Each gallery item consists of the featured image, the title, the date,
the content and the read more link. The title can be disabled
by checking this box. 


## Disable Post Dates 

Each gallery item consists of the featured image, the title, the date,
the content and the read more link. The date can be disabled
by checking this box. 


## Disable Post Content 

Each gallery item consists of the featured image, the title, the date,
the content and the read more link. The post content can be disabled
by checking this box. 


## Disable Read More 

Each gallery item consists of the featured image, the title, the date,
the content and the read more link. The read more link can be disabled
by checking this box. 

# Mashup Settings  

The mashup page allows you to create a page whose content is made up of the 
content of other pages. 


## Pages to Include 

Allows you to select a number of pages to include. The selected pages will 
be shown under the select box. You cam reorder the pages by dragging and 
dropping them to the correct place. The chosen order will be reflected on
the website as well.

## Disable Mashup Title 

The title of the actual mashup page can be shown above the gallery. 
If you choose to, you can disable this. 


## Show Mashup Content 

If you want to show additional details about the mashup, you can add 
content to the mashup page itself. This can be shown by checking this
box.  

# Shortcodes

Shortcodes are quick shortcuts to create custom content in the WordPress
editor. For example, a good looking CSS3 button with drop shadows and 
rounded corners would take a lot of code to create. Instead you can just use
[button text='I am Happy'] to create a great looking button. Here's a list 
of all the Shortcodes in your theme and how to use them. 

## Line  

This shortcode will generate a horizontal like used to separate content 
nicely. 

Example: [line]
Output:  A simple horizontal line

## Line Link  

This shortcode also creates a horizontal line, but enables you to add a link 
into the line, floated left or right. This is useful for creating 'to the 
top' navigation.

### Optional Parameters 

 - align: Change the alignment of the link. Can be 'left' or 'right'.
          Default: right
 - text:  Change the text of the link. Can be any text.
          Default: top
 - url:   Specify where the link should point to. Leave blank or use 'top'
          to make the link go to the top of the page. You can use 
          '#section' to link to am HTML tag with an id or use a regular
          URL to send the user to another page. 
          Default: top
        
  
Example: [linelink]
Output:  A horizontal link with a link to the top of the page floated right.

Example: [linelink align='left' text='get news' url='http://cnn.com']
Output:  A horizontal line with a link pointing to CNN floated to the left

## Highlight  

Wrapping this shortcode around some text will add a highlight effect to that
text. 

### Optional Parameters 

 - background: The background color of the highlight. This can be any CSS
               value like HEX colors (#ff9900), RGB colors (234,112,39) or 
               named colors (red).
               Default: Primary theme color
               
 - color:      The color of the text in the highlight. This can be any CSS
               value like HEX colors (#ff9900), RGB colors (234,112,39) or 
               named colors (red).         
               Default: white    
               
Example: [highlight]This text is pretty important[/highlight]
Output:  The text 'This text is pretty important' will be highlighted with 
         a blue background and it will have a white text color;
        
Example: [highlight background='red' color='white']This text is pretty 
         important[/highlight]
Output:  The text 'This text is pretty important' will be highlighted with 
         a red background and it will have a white text color;

## Columns  

Using this shortcode you can put your content into neat columns. Take care
as this shortcode has required parameters. You need to make sure that the
first and last column receives the parameters 'first' and 'last' 
respectively and that the column widths of all the columns in a row add up
to 12. 

### Required Parameters 

 - first: If this is the first column in the row you need to add 
          first='true' as a parameter.
          
 - last:  If this is the last column in the row you need to add 
          last='true' as a parameter
          
### Optional Parameters 

 - size:  The length of the column. This can be a value between 1 and 12. 
          You need to make sure that the size of all columns in a row add
          up to twelve exactly. If you would like three columns each would 
          have to have a size of 4. You could also have a column with a 
          width of 8 and another with a width of 4.
          Default: 6
          
 - align: The alignment of the content inside the column. This can be left
          right or center. 
          Default: left
          

Example: [column first='true'] This is content in the first column[/column]
         [column last='true'] Content of the second column[/column]
Output:  This will result in a two column layout because the default size 
         is 6 
         
Example: [column first='true' size='3'] This is content in the first 
         column[/column]
         [column size='3'] Content of the second column[/column]
         [column size='3'] Content of the third column[/column]
         [column last='true' size='3'] Content of the fourth column[/column]
Output:  This will result in a four column layout
                  
## Maps  

Maps allow you to quickly add a Google Maps map to your content. You can
specify the location, zoom, popup, height and width to make sure you can
fit it well into your content. 

### Required Parameters 

 - location: The address to center the map on. 
      
    
### Optional Parameters 

 - zoom:   Set the zoom level at which the map should be by default. The 
           accepted range is 0-24, the higher the zoom the closer in you go.
           Default: 10
           
 - popup:  Can be set to true or false. If set to true a white popup box
           will show the location on the map
           Default: false
           
 - height: Modify the height of the map. Any CSS value is accepted but we 
           recommend using pixels.
           Default: 400px
           
 - width:  Modify the width of the map. By default the map spans the 
 	       available space. This means that if you put a map into a column
 	       you don't have to worry about giving it a size, it will scale
 	       nicely. It accepts any CSS value, but leaving it at 100% is 
 	       recommended. 
 	       Default: 100%          
   

Example: [map location='The Netherlands, The Hague, Saturnusstraat']
Output:  Shows a map centered on the given location, at a zoom of 10

Example: [map location='Great Britain, London, Downing street 10' 
         zoom='18' popup='true']
Output:  Displays a map zoomed in at level 18 on 10 Downing street with a 
         popup displaying the address. 
         
## Messages  

Messages are displayed as alert boxes with a title and some text inside. 
They can be used to highlight something important, show an error or 
perhaps a success message. Message shortcodes are wrapped around the text 
you would like to use as the message


### Optional Parameters 

 - title:      If needed a title can be added. It will be shown above the 
               message in a slightly bolder style
               Default: none

 - type:       The type of message box you want to add. These are used only 
               to modify how the box and text is colored. If you want to use 
               a custom background and color you should use custom. If you 
               want to use the built in types, 'error', 'success' and 
               'alert' is available.
               Default: custom
               
 - color:      Defines the color of the text inside the message box. Accepts
               any CSS color value.
               Default: none
               
 - background: Defines the message box background color. Accepts any CSS 
               color value.
               Default: none  
               
 - align:      Defines the alignment of the text inside the message box. 
               'left','right' and 'center' is available.
               Default: left
               
 - gradient:   If set to true it creates a gradient from your given 
               background color. It makes the top of the gradient slightly 
               lighter and the bottom slightly darker than the given
               background. 
               Default: false
               
 - radius:     Sets the radius of the message box. If set to 0 the box will 
               have square edges. The higher the number the more rounded the 
               edges become.
               Default: none
               
 - textshadow: Sets the text shadow of the content. It can be set to dark or
               light. It should be set to dark if the text color is light 
               and vice versa. 
               Default:none
               
               
Example: [message type='error']You can't touch this, da da da dam[/error]    
Output:  This will display the message given in a red box

Example: [message type='success' gradient='true' radius='6']You can touch 
         this da da da dam[/message]
Output   This will display the given message in a green box which has a 
         smooth gradient and a 6px border radius. 
    
## Toggle  

The toggle shortcode allows you to create a section which can be hidden
or shown by clicking on the title. The shortcode is wrapped around the 
content which is to be toggled.

### Required Parameters 

 - title:   The title of the toggleable section which also acts as the 
            trigger which can be clicked to open/close the section.
            Default: none
            
### Optional Parameters         
         
 - default: The default state of the toggleable section. Can be set to 
            'open' or 'closed'. This is the state the section will be in
            when the page loads. 
            Default: closed
            
 - effect:  The effect to use to toggle the section. Can be 'slide', 'fade'
            or none.
            Default: slide
            

Example: [toggle title='Additional Resources']Some Content Here[/toggle]
Output:  Shows only the given title with a downward pointing arrow next to
         it which expands the section when clicked. 
         
Example: [toggle title='Sponsors Area' default='open' effect='fade']Some 
         Content Here[/toggle] 
Output:  Shows the title and the content which can be closed/opened by
         clicking the title. When triggered a hover effect will be used.         
     
## Button  

This shortcode allows you to create nice looking CSS 3 buttons very easily. 
It can receive a large number of parameters for full customization but there
are 8 presets built in which give you easy access to some common setups. 

### Optional Parameters 

 - title:      The text one the button. 
               Default: Read more
 - url:        The URL where the button takes the user. By default the
               user is taken to the top of the page.
               Default: #
               
 - preset:     Presets give you quick access to some good looking setups.
               The eight preset correspond to 8 colors. The available 
               values are: red, cyan, yellow, black, blue, green, purple
               orange. 
               
               Default: primary theme color
 - color:      Defines the color of the text on the button
               Default:none
               
 - background: Defines the background color of the button
               Default: primary theme color

 - gradient:   If set to true it creates a gradient from your given 
               background color or preset. It makes the top of the gradient 
               slightly lighter and the bottom slightly darker than the
               given background. 
               Default: false
               
 - radius:     Sets the radius of the button. If set to 0 the button will 
               have square edges. The higher the number the more rounded the 
               edges become.
               Default: none
               
 - textshadow: Sets the text shadow of the button text. It can be set to 
               dark or light. It should be set to dark if the text color is 
               light and vice versa. 
               Default:none

Example: [button title='Register Now' url='http://register.com']
Output:  This will give you a button which has the same background color as 
         your primary color and white text
         
Example: [button title='Sign Up' url='http://signup.com/' preset='red']
Output:  This will show a red button with white text

## Title  

The title shortcode allows you to create titles just like the page titles on
your website. 

Example: [title]Welcome to my site[/title]
Output:  The given text will be formatted as a page title

## Other Shortcodes

Some themes have additional shortcodes available. Take a look at the 
[The Beauty Salon Shortcodes](http://www.redfactory.nl/themes/The Beauty Salon/features/shortcodes/)
page for more information.

# Setting Up A Home Page

There are a number of ways to set up your home page depending on what you 
want to put on it. If your goal is to have a blog which shows the latest
posts then you'll be fine out of the box. Let's take a look at some other
scenarios below.

## Post List Home Page 

You might want to show posts on your home page, but not your latest ones. 
Let's say you want to show your latest posts from the "Animals" category
on the front page and you want to have a separate page which lists all 
posts. 

First, go to 'Pages->Add New' and create a page with the 'Post List' 
template. Make sure to save it as a draft so the post list options can be 
shown. 

Select the 'Animals' category from the dropdown in the post list settings, 
make sure include is checked below and publish the page. Let's call this
page the 'Animals Blog'.

Next, create another post list page but don't select any categories to
include or exclude. This page will list all your posts. Let's call it
'My Thoughts' and publish it as well. 

Go to 'Settings->Reading' and next to 'Front page displays' select the 
static page option. Set your 'Animals Blog' as the front page and your 
'My Thoughts' page as your posts page.

When anyone visits your front page they will see the post list listing all
your posts about animals. Depending on how your menus are set up you may
need to add a link to your posts page, see the 'Setting up Menus' section.


## Customized Front Page 

In many cases you don't want to list posts on your front page at all, you
want some static content to show visitors. This case is very similar to the
one above, except instead of creating a post list as our front page we 
simply use a page. 

Create a page named 'home' (it can be named anything) and add the content 
you'd like. Using columns and buttons can be a good way of making some 
vibrant front page content. When done make sure to publish the page. 

Create a page named 'blog' (this can also be named anything) and publish it.

Go to 'Settings->Reading' and next to 'Front page displays' select the 
static page option. Set your 'Home' page as the front page and your 
'Blog' page as your posts page.

When anyone visits your front page they will see the content of the 'Home'
page that you created. Depending on how your menus are set up you may
need to add a link to your posts page, see the 'Setting up Menus' section.

You can put some more elaborate home pages in place without duplicating 
content by using the Mashup page template. If you would like your home page
to be built out of an 'About Me' section, a 'My Services' section and a 
'Awesome Clients' section you can create these three as separate pages and 
pull them into one place with a mashup page. This way the content on these
pages is reachable separately but is mashed together on the front page. 

# Setting Up Menus

Your website contains two customizable menus, one in the header and one in 
the footer. By default these show all the pages you have created. This might 
not be what you want though since you might want to hide some pages, and 
if you have too many it would just be too much to put on the page. 

You can customize these menus by going to 'Appearance->Menus'. WordPress 
allows you to create as many menus as you'd like. Once you've created a menu
you can assign it to a location in the theme. 

First add a menu on the right side of the page. You can then add items to it 
using the controls on the left. You can also rearrange the items by dragging
and dropping them into the correct place. 

Once done, save the menu and use the 'Theme Locations' box on the top left 
to assign a menu to a location. Once complete your new menu should show up
in the location specified. 

Make sure to look at additional options offered in the menu. Once you add
a page to the menu for example you can click the arrow on its right side
to get further options. You can change the label which is handy if you want
to keep the name of the page in the admin but want to show users a different
name. 

# Setting up the Sidebar

WordPress allows you to set up your sidebar in any way you wish and gives 
you powerful controls to modify and tweak it at any time. Sidebars can be 
modified by going to 'Appearance->Widgets'. 

Your sidebars are shown on the right and the widgets you can use in them on
the left. By default you only have one sidebar named 'Sidebar' but you may
set up more in the Theme Settings (see Custom Sidebars in the Theme 
Settings). 

Just drag and drop widgets you want to use into the sidebar of your choice. 
If a sidebar is empty you may have to click the arrow next to its title 
first to open it up. 

Once a widget is in the sidebar you can click on the arrow next to it to 
expand the available options. 

WordPress has many widgets available by default and their usage should be 
pretty easy. We've also added a few for added flexibility, lets take a look.


## Custom Maps Widget 

Just like the map shortcode, this widget allows you to put a map inside 
your sidebar. Drag it in place and set up all the options you need. 


## Custom Contact Widget 

This widget allows you to put a nice looking contact section in your 
sidebar. You can add links to your social media profiles and more 
(email, phone, address).


## Featured Item Widget 

This widget allows you to put an arbitrary piece of featured content in 
your sidebar which doesn't have to be tied to one of your posts. You 
can upload an image, add a title, content and a link and it will be 
formatted nicely for you in the sidebar. 


## Custom Latest Posts Widget 

While WordPress does have a latest post widget, it only allows you to show
the latest posts from the whole set. This widget allows you to choose 
categories to show the latest posts from and gives you access to more 
options like disabling the title and/or dates. 

## Custom Twitter Widget 

This widget allows you to pull the Twitter stream of a given user into your
website. 

# Files Included in Your Theme

Your theme includes a number of files which are not used by WordPress, but 
were used for development. If you want to make modifications to the code you
may of course do so, and using some of these development files your job may 
be a bit easier. 

## LESS Files

Your theme contains a folder named 'less'. This folder includes all the LESS
files we used to build the stylsheets. LESS is a dynamic stylehseet language
and a superset of CSS. This means that all CSS is valid LESS code, but not 
all LESS code is valid CSS. 

To learn almost everything there is to know about LESS visit its webpage at
http://lesscss.org. While you can include LESS files in websites directly by
using an additional Javascript file, we always compile the LESS code into 
CSS for themes. 

'base.less' and 'base-admin.less' are files which are not used directly, 
they are included into other LESS files. 'style.less' generates the main 
stylesheet for your site, the compiled CSS from this file is 'style.css'
from your main directory. All other LESS files are compiled to the 'css'
directory.


## Javascript Files 

We only include the minified Javascript files but we have added the 
non-minified version for convenience. Non-minified versions make debugging
and adding features much easier.

If you have any questions about the Javascript files, we recommend visiting
the sites of the respective plugins, or if it is one of our files, going to
the support forum. 

# Modifying the Theme Code
 
We recommend modifying the code only if you are well versed in WordPress and
PHP in general. You are of course allowed to make any modifications you
wish, but we might not be able to provide extensive support for modified 
themes. 

If you would like to modify the styles of your site, please use custom.css
in the CSS folder. If you don't directly use the other CSS files it will
be much easier to troubleshoot problems in the future. 
 
Many files are inside the framework folder, please do not modify these. 
We have an automatic update in place which will overwrite the modifications
you make. 

# Frequently Asked Questions

Q: How can I modify additional style element of my website
A: You can easily add your custom styles into 'custom.css' which can be 
   found in the css folder. 
   
Q: The menus in my header and footer have too many elements / show all my 
   pages.
A: By default these menus are set up to show all the pages published. You 
   can customize each menu in 'Appearance->Menus'. For more information 
   read the 'Setting Up Menus' section in this readme file. 
   
Q: I created columns with shortcodes but the display is all over the place
A: There are two very important rules to follow when creating columns. You
   need to make sure that the total size of all columns in a row is exactly
   12. You can have two sixes or three fours or you can have a nine and a 
   three and so on. The other important rule is to make sure that the first
   and last column receive the first='true' and last='true' attributes
   respectively. Apart from the first and last column, no other column 
   should receive these parameters. 
   
Q: The Custom Twitter Widget doesn't show any tweets, even though the user I
   added exists
A: This widget will show recent tweets, if the person hasn't tweeted in a 
   long while it won't retrieve them. 
