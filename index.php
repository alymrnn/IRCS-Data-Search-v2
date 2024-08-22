<?php
include ('process/conn_ircs.php');
include ('plugins/system_plugins/header.php');
include ('plugins/system_plugins/preloader.php');
include ('plugins/system_plugins/navbar/index_navbar.php');
?>

<div class="content-wrapper" style="background: #F9F9F9;">
   <!-- Main content -->
   <section class="content">
      <div class="col-12 col-md-12 m-0 p-0">
         <div class="mt-4"></div>

         <div class="card mx-3">
            <!-- SEARCH FIELD -->
            <div class="card-body">
               <div class="row">

                  <div class="col-12 col-sm-4 col-md-3 mb-2">
                     <!-- date from -->
                     <label style="font-weight:normal;margin:0;padding:0;color:#000;">Datetime From</label>
                     <label class="m-0 p-0" style="color:#CA3F3F">*</label>
                     <input type="datetime-local" name="date_from" class="form-control" id="search_date_from"
                        placeholder="Date From" onfocus="(this.type='datetime-local')"
                        style="color: #525252;font-size: 15px;border-radius: .25rem;border: 1px solid #888888;background: #FFF;height:35px; width:100%;">
                  </div>
                  <div class="col-12 col-sm-4 col-md-3 mb-2">
                     <!-- date to -->
                     <label style="font-weight:normal;margin:0;padding:0;color:#000;">Datetime To</label>
                     <label class="m-0 p-0" style="color:#CA3F3F">*</label>
                     <input type="datetime-local" name="date_to" class="form-control" id="search_date_to"
                        placeholder="Date To" onfocus="(this.type='datetime-local')"
                        style="color: #525252;font-size: 15px;border-radius: .25rem;border: 1px solid #888888;background: #FFF;height:35px; width:100%;">
                  </div>
                  <div class="col-12 col-sm-4 col-md-2">
                     <!-- clear all button -->
                     <label></label>
                     <button class="btn btn-block d-flex justify-content-left" id="clear_btn"
                        onclick="clear_search_ircs_record()"
                        style="color:#fff;height:35px;background: #474747;font-size:15px;font-weight:normal;"
                        onmouseover="this.style.backgroundColor='#2D2D2D'; this.style.color='#FFF';"
                        onmouseout="this.style.backgroundColor='#474747'; this.style.color='#FFF';">
                        <i class="fas fa-trash" style="margin-top: 2px;"></i>&nbsp;Clear
                     </button>
                  </div>
                  <div class="col-12 col-sm-6 col-md-2 mb-2">
                     <!-- search button -->
                     <label></label>
                     <button class="btn btn-block d-flex justify-content-left" id="search_btn"
                        onclick="search_ircs_data_count(1)"
                        style="color:#fff;height:35px;border-radius:.25rem;background: #2D2D2D;font-size:15px;font-weight:normal;"
                        onmouseover="this.style.backgroundColor='#1D1D1D'; this.style.color='#FFF';"
                        onmouseout="this.style.backgroundColor='#2D2D2D'; this.style.color='#FFF';">
                        <i class="fas fa-search" style="margin-top: 2px;"></i>&nbsp;Search</button>
                  </div>
                  <div class="col-12 col-sm-4 col-md-2">
                     <!-- export button -->
                     <label></label>
                     <button class="btn btn-block d-flex justify-content-left" id="export_record"
                        onclick="export_ircs_data_count()"
                        style="color:#fff;height:35px;background: #7A5C94;font-size:15px;font-weight:normal;"
                        onmouseover="this.style.backgroundColor='#523B66'; this.style.color='#FFF';"
                        onmouseout="this.style.backgroundColor='#7A5C94'; this.style.color='#FFF';"><i
                           class="fas fa-download" style="margin-top: 2px;"></i>&nbsp;Export</button>
                  </div>
               </div>
            </div>
         </div>

         <!-- MAIN FIELD -->
         <div class="card mx-3">
            <div class="card-body">
               <p class="p-0 m-0" style="color:#525252"><i class="far fa-folder"></i>&nbsp;IRCS Data Table</p>
               <div class="col-sm-3">
                  <!-- view total count of data from table -->
                  <span id="count_view_ircs"></span>
               </div>

               <!-- table -->
               <div id="list_of_ircs_res" class="card-body table-responsive m-0 p-0" style="max-height: 400px;">
                  <table class="table col-12 mt-3 table-head-fixed text-nowrap table-hover" id="ircs_table"
                     style="background: #F9F9F9;">
                     <thead style="text-align: center;">
                        <th>#</th>
                        <th>Parts Name</th>
                        <th>Lot No.</th>
                        <th>IRCS Data Count</th>
                     </thead>
                     <tbody class="mb-0" id="list_of_ircs_data">
                        <tr>
                           <td colspan="10" style="text-align: center;">
                              <div class="spinner-border text-dark" role="status">
                                 <span class="sr-only">Loading...</span>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               <br>
               <div class="d-flex justify-content-sm-end">
                  <div class="dataTables_info" id="ircs_table_info" role="status" aria-live="polite"></div>
               </div>
               <div class="d-flex justify-content-sm-center">
                  <button type="button" class="btn" style="background: #032b43; color: #fff;" id="btnNextPage"
                     onclick="get_next_page()"
                     onmouseover="this.style.backgroundColor='#032031'; this.style.color='#FFF';"
                     onmouseout="this.style.backgroundColor='#032b43'; this.style.color='#FFF';">Load More</button>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>

<?php
include ('plugins/system_plugins/footer.php');
include ('plugins/system_plugins/js/index_script.php');
?>