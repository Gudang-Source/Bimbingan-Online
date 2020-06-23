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

      <!-- Theme style -->
      <link rel="stylesheet" href="<?php echo base_url().'assets/';?>dist/css/AdminLTE.min.css">

      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
         <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
         <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
      <style type="text/css">

       @media print {
         .page-header{
            margin-top: 50px;
            page-break-before: always;
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

       }
      </style>
  </head>
  <body>
    <div class="wrapper">
<?php
foreach ($query as $row) {
?>
      <div class="row">
         <div class="col-lg-8">
         <div class="invoice">
         <h2 class="page-header">   
            Employee <?php echo !empty($row->CompleteName) ? $row->CompleteName : '';?>
         </h2>
          <div class="box-body">
            <table class="table table-condensed">
               <tbody>
               <tr>
                  <th width="120px" scope="row">Employee ID</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($row->EmployeeID) ? $row->EmployeeID : '';?></td>
               </tr>
               <tr>
                  <th scope="row">Last Name</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($row->LastName) ? $row->LastName : '';?></td>
               </tr>
               <tr>
                  <th scope="row">First Name</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($row->FirstName) ? $row->FirstName : '';?></td>
               </tr>
               <tr>
                  <th scope="row">Sex</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($row->Sex) ? $row->Sex : '';?></td>
               </tr>
               <tr>
                  <th scope="row">Title</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($row->Title) ? $row->Title : '';?></td>
               </tr>
               <tr>
                  <th scope="row">TitleOfCourtesy</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($row->TitleOfCourtesy) ? $row->TitleOfCourtesy : '';?></td>
               </tr>
               <tr>
                  <th scope="row">BirthDate</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($row->BirthDate) ? $row->BirthDate : '';?></td>
               </tr>
               <tr>
                  <th scope="row">HireDate</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($row->HireDate) ? $row->HireDate : '';?></td>
               </tr>
               <tr>
                  <th scope="row">Address</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($row->Address) ? $row->Address : '';?></td>
               </tr>
               <tr>
                  <th scope="row">City</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($row->City) ? $row->City : '';?></td>
               </tr>
               <tr>
                  <th scope="row">Region</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($row->Region) ? $row->Region : '';?></td>
               </tr>
               <tr>
                  <th scope="row">Country</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($row->Country) ? $row->Country : '';?></td>
               </tr>
               <tr>
                  <th scope="row">PostalCode</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($row->PostalCode) ? $row->PostalCode : '';?></td>
               </tr>
               <tr>
                  <th scope="row">HomePhone</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($row->HomePhone) ? $row->HomePhone : '';?></td>
               </tr>
               <tr>
                  <th scope="row">Extension</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($row->Extension) ? $row->Extension : '';?></td>
               </tr>
               <tr>
                  <th scope="row">Notes</th>
                  <td width="5px"> : </td>
                  <td><?php echo !empty($row->Notes) ? $row->Notes : '';?></td>
               </tr>
               </tbody>
            </table>         
        </div>

         </div>
         </div> 
      </div>
<?php
}
?>      
    </div><!-- ./wrapper -->

  </body>
</html>
