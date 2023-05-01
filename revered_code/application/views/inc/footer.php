
<footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">  
            <div class="text-center">
               <div class="copyright"> <p class="copyright_text text-center">© Copyright <strong><span>CyberBukit Membership</span></strong>. All Rights Reserved </p>
               </div>
            </div>
        </div>
      </div>
    </div>
  </footer>
  
  


  <?php include("inc/footer.php")  ?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <script src="<?= base_url("install/assets/js/main.js")?>"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-2.1.3.min.js"></script>
<script>
  function stopVideo(id) {
    var player = new YT.Player('player-'+id);
    player.stopVideo();
}
</script>

  
  </body>
</html>
