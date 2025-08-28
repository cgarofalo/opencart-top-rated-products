# OpenCart Top Rated Products
An [OpenCart](https://www.opencart.com/) 4.1 module to add Top Rated products to any layout.

## About this extension

This extension is a clone of the default OpenCart 4.1 "Latest" module.

The following changes have been made:

1) The title of the module is now "Top Rated". 
2) The query has been modified to order results by `rating` instead of `date_added`.
3) The field validation for the admin settings are stricter. Limit will only accept an integer bewteen 1 and 100. The width and height fields will only accept integers between 200 and 3000.

## How to use

1) Download this repository and compress the contents of the `toprated` directory into a zip file. The `install.json` file should be at the root. Name it `toprated.ocmod.zip`. Note: OpenCart 4.1 is picky about how the file is compressed. You may need to use [WinZip](https://www.winzip.com/) to compress it, if you get errors while trying to install it.
2) In OpenCart 4.1 admin, go to Extensions >> Installer. Upload the `toprated.ocmod.zip` file.
3) Go to Extenstions >> Extensions and filter for "Modules". Install the "Top Rated" module by clocking the [+] button.
4) Add a "Top Rated" module by clicking the "Edit" button (pencil icon). Fill out the settings. Set status to "true/on" and save it.
5) Go to Design >> Layouts and add the module to a layout. Save the changes to the layout.
6) Visit the frontend of your website where the module is installed. You should see a "Top Rated" section showcasing your site's highest-rated products, ordered by rating and then by title.

## Warranty and licence

This extension is released under version 3 of the GNU General Public License. Use it at your own risk—there are no guarantees, and any issues that arise are solely your responsibility.
