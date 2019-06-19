## Installation

Clone the repository

<pre><code>https://github.com/siddhantthapa23/module-permission-role.git</pre></code>

Switch to the repo folder

<pre><code>cd module-permission-role</pre></code>

Install all the dependencies using composer

<pre><code>composer install</pre></code>

Copy the example env file and make the required configuration changes in the .env file

<pre><code>cp .env.example .env</pre></code>

Generate a new application key

<pre><code>php artisan key:generate</pre></code>

<pre><code>php artisan serve</pre></code>

You can now access the server at http://localhost:8000