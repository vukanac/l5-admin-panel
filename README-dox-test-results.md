Authentication
 [x] test i can create an account
 [x] test i can login and logout
 [x] test i cannot login with wrong username and password LoginFailed
 [x] test i can see forgot password link and reset password email form
 [x] Reset password wrong email
 [x] test i can see reset password with emailed token
 [x] test i cannot reset password without confirmation password
 [x] test i am redirect to login if i try to view companies list without logging in
 [x] test i am redirect to login if i try to view users list without logging in

Companies
 [x] test companies are displayed on the dashboard
 [x] test users see companies of other users

CompanyCreateForm
 [x] test company can be created from form
 [x] test company cannot be created with long name

CompanyDelete
 [x] test company can be deleted
 [x] test company of other user can be deleted

CompanyDetails
 [x] test responds 404 if id does not exist
 [x] test company details can be seen
 [x] test company details of other user can be seen

CompanyEdit
 [x] test returns 404 if id does not exist
 [x] test company edit page is accessible
 [x] test see edit button in companies list
 [x] test company is edited

Homepage
 [x] Welcome
 [x] Error page not found status code
 [x] Error page not authorised status code

MainMenu
 [x] test Register option is in menu
 [x] test Login option is in menu
 [x] test Logout option is in menu
 [x] test company option is in menu
 [x] test user option is in menu
 [x] test user profile option is in menu

UserCreateForm
 [x] test user can be created from form

UserRolesCompany
 [x] test every user role can see any company details
 [x] test owner can create edit and delete company
 [x] test admin can create company
 [x] test admin can edit company
 [x] test admin can delete company
 [x] test manager cannot create company
 [x] test manager can edit company
 [x] test manager can delete company
 [x] test author cannot create company
 [x] test author cannot edit company dont see edit company button
 [x] test author cannot delete company

UserRolesUser
 [x] test owner can create user but not owner
 [x] test owner can delete user
 [x] test owner cannot delete owner
 [x] test owner can edit user and change user role except owner
 [x] test owner can edit self
 [x] test owner cannot change his own role
 [x] test admin can create user with role except owner
 [x] test admin can edit user and change user role
 [x] test admin cannot edit owner
 [x] test admin can edit self
 [x] test admin can delete user
 [x] test admin cannot delete owner
 [x] test manager cannot create user
 [x] test manager can edit user
 [x] test manager can edit self
 [x] test manager cannot change user role
 [x] test manager cannot edit owner
 [x] test manager cannot delete user
 [x] test author cannot create user
 [x] test author cannot edit user
 [x] test author can edit self
 [x] test author cannot delete user
 [x] test viewer cannot create user
 [x] test viewer cannot edit user
 [x] test viewer can edit self
 [x] test viewer cannot delete user

User
 [x] test responds 404 if id does not exist
 [x] test user can see profile
 [x] test user can edit profile
 [x] test show one user details
 [x] test user can see other user details
 [x] test every user role can get other user details
 [x] test user can see list of users

