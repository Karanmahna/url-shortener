Requirement Tools : 
    PHP [i used latest 8.5 version]
    Composer
    MySQL
    Git
    A local server such as XAMPP


Install PHP dependencies using Composer:
composer install

Update database setting in .env 
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1 
    DB_PORT=3306 
    DB_DATABASE=url_shortener 
    DB_USERNAME=root
    DB_PASSWORD=

Run migrations  => php artisan migrate

Seed dB => php artisan db:seed [For superAdmin]
   For superadmin creds-> email ->superadmin@example.com 
                        Password => super@123 [i used this same password for all users]

Last -> php artisan serve [access /login endpoint to run project]

###### After LOgging successfully #########
@@@Super admin Dashboard
 --> Super admin Dashboard open where you can invite any admin/companies can be created . 
 --> A link is generated for registration. After registeration it will redirect to login with success message so you can login with new user. 
 --> All generated URLs are also shown with thier clients name and created date . 
 --> which client created how many users , generated how many urls , hit how many time All these details are also listed in dashboard . 


@@@@Admin Dashboard
--> Number of generated URLs shown created in thier own company
--> All connected member are also listed created in thier own company
--> Admin can generate URls
--> Admin can create admin/member for his own company


@@@@ Member Dashboard
--> Number of generated URLs shown created in thier own company
--> Members can generate short URLs.


// Additional
 Created a common dynamic view all functionality for admin/member/urls  listings
 Created a common dynamic invitation process for admins/superadmin. 