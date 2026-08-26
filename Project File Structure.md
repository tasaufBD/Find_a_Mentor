Project File Structure:

Mentors/



**Common:**

db.php			-Done

index.php			-Done

register.php		-Done

login.php			-Done

logout.php			-Done





**Student:**

student\_dashboard.php		-Done, **Need Modification.**



student\_profile\_view.php		View own profile

student\_profile\_edit.php		Edit own profile (always UPDATE, per our earlier fix)

student\_search\_mentors.php	Search/filter form + results container. This is the one file with a <script> block for the AJAX call

student\_my\_requests.php		List of session requests the student has sent, with status (needed so students can actually see if they were accepted — not in your original list but required to close the loop)



**Mentor**



mentor\_dashboard.php		-Done, **Need Modification.**

mentor\_profile\_edit.php		Edit own profile

mentor\_requests.php			View pending session requests, Accept/Reject buttons



**Shared view** (used by both student and mentor/admin)



view\_mentor.php?id=X		- Done.



**Admin**



admin\_dashboard.php		-Done, **Need Modification.**

admin\_manage\_users.php		List/delete users (like their viewStudent.php + deleteStudent.php combined)

admin\_verify\_mentors.php	-Done.



**AJAX endpoint			-Implement later.(next week)**



ajax\_search\_mentors.php		Takes filter values, queries mentor\_profiles, returns an HTML fragment (table rows) that JS injects into student\_search\_mentors.php — no full page reload

