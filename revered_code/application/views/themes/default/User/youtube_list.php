<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header');

// Load the database library
$this->load->database();

// Retrieve the data from the table

// $query = $this->db->get('youtube');

$query = $this->db->select('*')
                  ->from('new_youtube')
                  ->order_by('display_order')
                  ->get();





?>

<div class="container-fluid">
<div class="row">
    <div class="col-lg-4 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('Youtube Videos')?></h1>
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
                    <th width="8%"><?= my_caption('Id') ?></th>
                    <th width="15%"><?= my_caption('Youtube ID') ?></th>
                    <th width="10%"><?= my_caption('Thumbnail file name') ?></th>
                    <th width="15%"><?= my_caption('Upload Thumbnail') ?></th>
                    <th width="52%"><?= my_caption('Description') ?></th>
                    <th width="10%"><?= my_caption('Delete') ?></th>
                    <th width="10%"><?= my_caption('Edit') ?></th>
                    <th width="10%"><?= my_caption('Up') ?></th>
                    <th width="10%"><?= my_caption('Down') ?></th>

                </tr>
            </thead>
            <?php
            $i = 1;
            $previous_id=0;
            $pre_order=0;
            foreach ($query->result() as $row) {

                $next_row = $query->next_row();
                $next_id = $next_row ? $next_row->id : 0;
                $next_order = $next_row ? $next_row->display_order : 0;


                echo '<tr>';
                echo '<td>' . $row->id . '</td>';
                echo '<td>' . $row->youtube_id . '</td>';
                echo '<td style="max-width: 200px; word-wrap: break-word;">' . $row->thumbnail_name . '</td>';
                echo '<td style="max-width: 200px; word-wrap: break-word;">'.$row->thumbnail_file.'</td>';
                echo '<td style="max-width: 200px; word-wrap: break-word;">' . $row->description . '</td>';
                echo '<td><a href="#" onclick="confirmDelete('.$row->id.')">'.my_caption('Delete'). '</a></td>';
                echo '<td><a href="' . base_url('youtube/edit/' . $row->id) . '">' . my_caption('Edit') . '</a></td>';
                echo '<td>';
                if ($i > 1) { // Show move up button for all rows except the first row
                    echo '<a href="' . base_url('youtube/move_up/' . $row->id . '?param1=' . $previous_id  . '&param2=' .  $row->display_order . '&param3=' .  $pre_order) . '"><i class="fas fa-arrow-up"></i></a>';
                }
                echo '</td>';
                echo '<td>';
                if ($i < $query->num_rows()) { // Show move down button for all rows except the last row
                    echo '<a href="' . base_url('youtube/move_down/' . $row->id . '?param1=' . $next_id  . '&param2=' .  $row->display_order . '&param3=' .  $next_order) . '"><i class="fas fa-arrow-down"></i></a>';
                }
                echo '</td>';

				echo '</tr>';

        $pre_order=$row->display_order;
        $previous_id=$row->id;

				$i++;
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
