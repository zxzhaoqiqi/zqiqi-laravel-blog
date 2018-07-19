## 1.搭建
`composer create-project --prefer-dist laravel/laravel blog` 

## 2.安装
`composer install`

## 3.生成key
`php artisan key:generate`   

## 4.创建seeder
`php artisan make:seeder UsersTableSeeder`
会在database/seeds 文件夹下生成seeder文件

## 5.数据库增加表前缀
1. `.env`文件增加`DB_PREFIX=lv_`
2. 修改`config/database.php`文件 connections -> mysql `'prefix' => env('DB_PREFIX', ''),`

## 6.创建migrations
`php artisan make:migration create_category_table`
会在database/migrations 文件夹下生成migration文件

## 7.