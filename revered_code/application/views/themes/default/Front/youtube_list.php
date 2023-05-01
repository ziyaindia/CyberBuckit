<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header');

// Load the database library
$this->load->database();

// Retrieve the data from the table
$query = $this->db->get('youtube');
?>

<div class="container-fluid">
<div class="row">
    <div class="col-lg-4 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('My List')?></h1>
	</div>
    <div class="col-lg-8 text-right">
	  <button type="button" class="btn btn-primary mr-2" onclick="window.location.href='<?=base_url('user/youtube_new')?>'"><?=my_caption('Add New')?></button>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('Youtube List')?></h6>








        </div>
        <div class="card-body">
		  <div class="table-responsive">
		    <table id="" class="table table-bordered">
			  <thead>
			    <tr>
				<th width="8%"><?=my_caption('Order')?></th>
				  <th width="15%"><?=my_caption('Youtube ID')?></th>
				  <th width="10%"><?=my_caption('Thumbnail file name ')?></th>
				  <th width="15%"><?=my_caption('Upload Thumbnail')?></th>
				  <th width="52%"><?=my_caption('Description')?></th>
				  <th width="10%"><?=my_caption('Action ')?></th>
				  <th width="10%"><?=my_caption('  ')?></th>


			    </tr>
			  </thead>
			  <?php  foreach ($query->result() as $row) {
        echo '<tr>';
        echo '<td>'.$row->id.'</td>';
        echo '<td>'.$row->youtube_id.'</td>';
		echo '<td>'.$row->thumbnail_name.'</td>';
		echo '<td>'.$row->thumbnail_file.'</td>';
		echo '<td>'.$row->description.'</td>';
		echo '<td><a href="#" onclick="confirmDelete('.$row->id.')">'.my_caption('Delete').'</a></td>';

		echo '<td><a href="'.base_url('youtube/edit/'.$row->id).'">'.my_caption('Edit').'</a></td>';


        echo '</tr>';
    }

	?>
			</table>
          </div>
		</div>
      </div>
	</div>
  </div>
</div>

<script>
function confirmDelete(id) {
  if (confirm('Are you sure you want to delete this record?')) {
    window.location.href = '<?php echo base_url("youtube/delete/"); ?>' + id;
  }
}
</script>

<?php my_load_view($this->setting->theme, 'footer')?>