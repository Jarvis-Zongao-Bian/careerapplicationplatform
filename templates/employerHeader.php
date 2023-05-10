<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>CareerHub</title>

	<link rel="stylesheet" href="css/style.css">
</head>

<style>

* {
  box-sizing: border-box;
}

/* Style the body */
body {
  font-family: Arial;
  margin: 0;
}

/* Header/logo Title */
.header {
  padding: 20px;
  text-align: center;
  background: royalblue;
  color: white;
}

/* Style the top navigation bar */
.navbar {
  display: flex;
  background-color: royalblue;
}

/* Style the navigation bar links */
.navbar a {
  color: white;
  padding: 14px 20px;
  text-decoration: none;
  text-align: center;
}

/* Change color on hover */
.navbar a:hover {
  background-color: #ddd;
  color: black;
}

/* Table setup */
table, th, td {
  border: 1px solid black;
}

table {
  width: 100%;
}

/* Input style */
input[type=text] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  box-sizing: border-box;
  border: 3px solid #ccc;
  -webkit-transition: 0.5s;
  transition: 0.5s;
  outline: none;
}

input[type=text]:focus {
  border: 3px solid #555;
}

/* Select and option setup */
select option {
  margin: 40px;
  background: rgba(0, 0, 0, 0.3);
  color: #fff;
  text-shadow: 0 1px 0 rgba(0, 0, 0, 0.4);
}

/* Column container */
.row {  
  display: flex;
  flex-wrap: wrap;
}

/* Create two unequal columns that sits next to each other */
/* Sidebar/left column */
.side {
  flex: 30%;
  background-color: #f1f1f1;
  padding: 20px;
}

/* Main column */
.main {
  flex: 70%;
  background-color: white;
  padding: 20px;
}

.up {
  flex: 100%;
  text-align: center;
}

/* Fake image, just for this example */
.fakeimg {
  background-color: #aaa;
  width: 100%;
  padding: 20px;
}

/* Footer */
.footer {
  padding: 20px;
  text-align: center;
  background: #ddd;
}

/* Responsive layout - when the screen is less than 700px wide, make the two columns stack on top of each other instead of next to each other */
@media screen and (max-width: 700px) {
  .row, .navbar {   
    flex-direction: column;
  }
}
</style>

<body>


<!-- Header -->
<div class="header">
	<h1>CareerHub</h1>
</div>


<!-- Navigation Bar -->
<div class="navbar">
  <a href="postJob.php"><strong>Post Jobs</strong></a>
  <a href="viewJob.php"><strong>View Job Posts</strong></a>
  <a href="viewApplication.php"><strong>View Applications</strong></a>
  <a href="viewOffer.php"><strong>View Offers</strong></a>
  <a href="personalInfo.php"><strong>Personal Information</strong></a>
  <a href="accountInfo.php"><strong>Account Information</strong></a>
  <a href="payInfo.php"><strong>Payment Information</strong></a>
    <a href="contact.php"><strong>Contact Us</strong></a>
  <a href="../logout.php"><strong>Log out</strong></a>
</div>