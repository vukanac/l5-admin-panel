#### Laravel Cron

Set Schedules in app/Console/Kernel.php (schedule method).

Add only one entry to system cron for scheduling this project.

More advanced is to have one batch/bash script for all projects
whixh only will be called from cron.

##### Windows

    schtasks /create /sc minute /mo 1 /tn "Task Name" /tr c:\cron-jobs\laravel-projects-every-minute.bat

And in it we will put:

    CALL php /path/to/artisan schedule:run >> cron.log

Looks like this is the nices way, to run scheduled job in background without pup-up cmd window.

    schtasks /create /sc minute /mo 1 /tn "Task Name" /tr "start /B c:\cron-jobs\laravel-projects-every-minute.bat"


##### Linux

Here is the only Cron entry you need to add to your server:

    * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1

