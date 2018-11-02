# moodle-tool_inactive_user_cleanup

This plugin deletes or anonymizes long time inactive or suspended user accounts.

## Usage

Put the directory inactive_user_cleanup within your moodle/admin/tool/ dir. Go to the admin panel and have the plugin database update itself.
You will now be able to access Server -> User Cleanup (admin/tool/inactive_user_cleanup/).

### Menu

There are multiple options for admins to consider. You can setup the time in days after what period users will be filtered and processed.
Next you will see the field **Days before deletion**, this is inportant if you want to warn your users before removing them from the database.
* The default is -1, we will not send the user a notification and just process its account.
* You can add 0 if you do not want to process the user further but want to notify him / her.
* Adding a number triggers a waiting period after notifications were sent to the user.

The next checkbox allows to filter applicable users, either by inactivity as in last login or by suspension state.
Last you can choose if the normal moodle deletion routine is triggered or userdata is kept but anonymized after expiration.

Further down you will see an input area for email subject and text. This notification will be sent to your users if applicable.

### Usecases

The original authors [Dualcube](https://github.com/dualcube) explain the plugin usage like this:
In the first step admin user of the site setup days of inactivity and drafting notification mail for all users from the Site administration > Server > User Cleanup
. If an inactive user is found he/she gets a notification mail.
In second step if the user still has not accessed the moodle site within the time span which is mentioned in the notification mail.
Then the deletion process starts. The particular inactive user account entry is removed with next run of this cleanup process which is automatically or manually run by cron process.

This plugin has been significally extended to be used by the ÖAMTC.
Now it is possible to cleanup users that have been suspended for a long period of time and not only inactive users that have not logged in.
Also we allow to keep anonymized user data as to not disturb statistical data. You can check the box to trigger the moodle delete function as well.

## Authors

* **Dualcube** - *Initial work* - [Dualcube](https://github.com/dualcube)
* **michaelpollak** - [michaelpollak](https://github.com/michaelpollak)