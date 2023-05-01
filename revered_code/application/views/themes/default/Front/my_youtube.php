<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'Front/header')?>
    <section class="section-header bg-primary text-white pb-9 pb-lg-13 mb-4 mb-lg-6">
	    <div class="container">
          <div class="row justify-content-center">
            <div class="col-12 col-md-8 text-center">
                <h1 class="display-2 mb-3"><?=my_caption('front_youtube_form_title')?></h1>
            </div>
		     </div>
		   </div>
		<div class="pattern bottom"></div>
	  </section>
      <!-- <div class="section section-lg pt-0">
	    <div class="container mt-n8 mt-lg-n13 z-2">
		  <div class="row justify-content-center">
		    <div class="col-12">
			  <div class="card border-light shadow-soft p-2 p-md-4 p-lg-5"> -->
			    <div class="card-body">


<!-- Video Section Start -->

          <?php
// Load the database library
$this->load->database();

// Retrieve the data from the table
// $thumbnails = $this->db->get('youtube');
$thumbnails = $this->db->select('*')
                  ->from('new_youtube')
                  ->order_by('display_order')
                  ->get();
$you=$thumbnails->result();
?>
<!-- Video Section Start -->
<section id="videopage" class="thumbnail">
      <div class="container" >
        <div class="row d-flex align-items-center ">
        <?php foreach($thumbnails->result() as $value):?>
          <div class="col-lg-3 col-md-6 d-flex justify-content-center" >
            <div class="thumbnail-box" data-toggle="modal" data-target="#exampleModalCenter<?php echo $value->id ?>">
              <div class="icon">
                 <?php if ($value->thumbnail_file): ?>
                 <img src="<?php echo base_url('upload/play.png'); ?>" class="play_btn img-fluid"  />

                 <img src="<?php echo base_url('upload/'.$value->thumbnail_file); ?>" class="img-fluid thumbimg" /><br>
                  <?php endif; ?>
              </div>
                  <?php if ($value->youtube_id): ?>
                  <p><?php echo $value->description ?></p>
                  <?php endif; ?>
            </div>
          </div>
          <?php endforeach;?>
      </div>
    </section>
<!-- Video Section End -->

 <!-- Modal Start -->
 <?php foreach($thumbnails->result() as $value):?>
    <div class="modal fade" id="exampleModalCenter<?php echo $value->id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Play Youtube Video</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="stopVideo('<?php echo $value->id ?>')" data-id="<?php echo $value->id ?>">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
                <?php
                        $youtubeID=$value->youtube_id;
                        if ($value->youtube_id):
                        $embedURL = 'https://www.youtube.com/embed/'.$value->youtube_id.'';?>
                      <div id="player-<?php echo $value->id ?>"></div>

                        <?php endif; ?>
          </div>
            <div class="modal-footer">
            </div>
        </div>
      </div>
    </div>
<?php endforeach;?>
<!-- Modal End -->


<!-- Pagination Start -->
    <section class="pagi">
      <div class="container">
        <div class="row">
          <div class="col-12">
          <div class="pagination">
            <ul>
                <li class="prev" id="prev"><a href="#">Prev</a></li>
                <li class="next" id="next"><a href="#">Next</a></li>
                <li class="page active"><a href="#">1</a></li>
                <li class="page"><a href="#">2</a></li>
                <li class="page"><a href="#">3</a></li>
            </ul>
          </div>
          </div>
        </div>
      </div>
    </section>

           <!-- </div>
              </div>
            </div>
          </div>
        </div> -->
      </div>
    <!-- Pagination End -->
    <?php //include("inc/footer.php")  ?>
     <script>
var players = {};

function onYouTubeIframeAPIReady() {
  <?php foreach($thumbnails->result() as $value):?>
  players['<?php echo $value->id ?>'] = new YT.Player('player-<?php echo $value->id ?>', {
    videoId: '<?php echo $value->youtube_id ?>',
    playerVars: {
      'autoplay': 0,
      'controls': 1
    }
  });
  <?php endforeach; ?>
}

function stopVideo(id) {
  if (players[id]) {
    players[id].stopVideo();
  }
}


</script>
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <script src="<?= base_url("assets/themes/default/front/js/main.js")?>"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="https://www.youtube.com/iframe_api"></script>


<?php my_load_view($this->setting->theme, 'Front/social_part')?>
<?php my_load_view($this->setting->theme, 'Front/footer')?>

