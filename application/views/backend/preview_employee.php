<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Northwind</title>
      <!-- Tell the browser to be responsive to screen width -->
      <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      <!-- Bootstrap 3.3.5 -->
      <link rel="stylesheet" href="<?php echo base_url().'assets/';?>bootstrap/css/bootstrap.min.css">

      <!-- Font Awesome -->
      <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>plugins/font-awesome-4.1.0/css/font-awesome.min.css">
      <!-- Ionicons -->
      <link rel="stylesheet" href="<?php echo base_url().'assets/'; ?>plugins/ionicons/2.0.1/css/ionicons.min.css">
      <!-- Theme style -->
      <link rel="stylesheet" href="<?php echo base_url().'assets/';?>dist/css/AdminLTE.min.css">

      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
         <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
         <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
      <style type="text/css">
      .center-block {
         margin: 0 auto;
      }

      .table>tbody>tr>th,
      .table>tbody>tr>td, 
      .table>tfoot>tr>td, 
      .table>tfoot>tr>th, 
      .table>thead>tr>td, 
      .table>thead>tr>th {   
         border-top: none;
         border-bottom: 1px solid #ddd;
       }
      </style>
  </head>
  <body>
    <div class="wrapper">
      <div class="row">
         <div class="col-lg-8">
         <section class="invoice">
         <h2 class="page-header">   
            Employee <?php echo !empty($CompleteName) ? $CompleteName : '';?>
         </h2>
          <div class="box-body">
            <table class="table table-condensed">
               <tbody>
               <tr>
                  <th width="120px" scope="row">Employee ID</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($EmployeeID) ? $EmployeeID : '';?></td>
               </tr>
               <tr>
                  <th scope="row">Last Name</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($LastName) ? $LastName : '';?></td>
               </tr>
               <tr>
                  <th scope="row">First Name</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($FirstName) ? $FirstName : '';?></td>
               </tr>
               <tr>
                  <th scope="row">Sex</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($Sex) ? $Sex : '';?></td>
               </tr>
               <tr>
                  <th scope="row">Title</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($Title) ? $Title : '';?></td>
               </tr>
               <tr>
                  <th scope="row">TitleOfCourtesy</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($TitleOfCourtesy) ? $TitleOfCourtesy : '';?></td>
               </tr>
               <tr>
                  <th scope="row">BirthDate</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($BirthDate) ? $BirthDate : '';?></td>
               </tr>
               <tr>
                  <th scope="row">HireDate</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($HireDate) ? $HireDate : '';?></td>
               </tr>
               <tr>
                  <th scope="row">Address</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($Address) ? $Address : '';?></td>
               </tr>
               <tr>
                  <th scope="row">City</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($City) ? $City : '';?></td>
               </tr>
               <tr>
                  <th scope="row">Region</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($Region) ? $Region : '';?></td>
               </tr>
               <tr>
                  <th scope="row">Country</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($Country) ? $Country : '';?></td>
               </tr>
               <tr>
                  <th scope="row">PostalCode</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($PostalCode) ? $PostalCode : '';?></td>
               </tr>
               <tr>
                  <th scope="row">HomePhone</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($HomePhone) ? $HomePhone : '';?></td>
               </tr>
               <tr>
                  <th scope="row">Extension</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($Extension) ? $Extension : '';?></td>
               </tr>
               <tr>
                  <th scope="row">Notes</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($Notes) ? $Notes : '';?></td>
               </tr>
               </tbody>
            </table>         
        </div>

         </section>
         </div> 
      </div>
    </div><!-- ./wrapper -->
  </body>
</html>
