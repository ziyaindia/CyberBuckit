<?php
class Youtube extends MY_Controller {

   public function save_video() {

      // print_r($_POST);
      // die;

      $config['upload_path'] = './upload/'; // Set the upload directory
      $config['allowed_types'] = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG'; // Set the allowed file types
      $config['max_size'] = 5000; // Set the maximum file size in kilobytes

      $this->load->library('upload', $config);

      if (!$this->upload->do_upload('thumbnail_file')) {
         // If file upload fails, show error message
         $error = array('error' => $this->upload->display_errors());
         $this->load->view('youtube_edit', $error);
      } else {


         $order_query=$this->db->select_max('display_order', 'max_display_order')
				->from('new_youtube')
        		->get();
     $result = $order_query->row();
	 $max_display_order = $result->max_display_order;
         $order= $max_display_order + 1;

         // If file upload succeeds, insert data into database and redirect to success page
         $data = array(
            'youtube_id' => $this->input->post('youtube_id'),
            'thumbnail_name' => $this->input->post('thumbnail_name'),
            'thumbnail_file' => $this->upload->data('file_name'), // Get the uploaded file name
            'description' => $this->input->post('description'),
            'display_order' => $order
         );
         $this->db->insert('new_youtube', $data);
         redirect('user/youtube');
      }
   }
   public function delete($id) {
      $this->db->where('id', $id);
      $this->db->delete('new_youtube');

      redirect('user/youtube');
   }

   public function edit($id) {
      // Fetch the video record from the database based on its ID
      $video = $this->db->get_where('new_youtube', array('id' => $id))->row();

      // Pass the video data to the edit view
      $data['video'] = $video;
      $this->load->view('youtube_edit', $data);
   }

   public function update($id) {
      // Get the updated video data from the form
      $data = array(
         'youtube_id' => $this->input->post('youtube_id'),
         'thumbnail_name' => $this->input->post('thumbnail_name'),
         'description' => $this->input->post('description')
      );

      // If a new thumbnail file was uploaded, update the file name
      if (!empty($_FILES['thumbnail_file']['name'])) {
         $config['upload_path'] = './upload/'; // Set the upload directory
         $config['allowed_types'] = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG'; // Set the allowed file types
         $config['max_size'] = 5000; // Set the maximum file size in kilobytes

         $this->load->library('upload', $config);

         if (!$this->upload->do_upload('thumbnail_file')) {
            // If file upload fails, show error message
            $error = array('error' => $this->upload->display_errors());
            $this->load->view('user/youtube_new', $error);
            return;
         } else {
            // If file upload succeeds, update the file name
            $data['thumbnail_file'] = $this->upload->data('file_name');
         }
      }

      // Update the video record in the database
      $this->db->where('id', $id);
      $this->db->update('new_youtube', $data);

      // Redirect back to the user's YouTube page
      redirect('user/youtube');
   }

   public function move_up($id) {
      print_r($id);
      $previous_id = $this->input->get('param1');
      $order = $this->input->get('param2');
      $pre_order = $this->input->get('param3');
      // echo ' next_id= '.$param1.' this '.$order.'  '.$pre_order;
      // die;
      $pre_data = array(
         'display_order' => $pre_order
       );

       $this->db->where('id', $id);
       $this->db->update('new_youtube', $pre_data);

       $new_data = array(
         'display_order' => $order
       );

       $this->db->where('id', $previous_id);
       $this->db->update('new_youtube', $new_data);

       redirect('user/youtube');
  }


  public function move_down($id) {
   print_r($id);
   $next_id = $this->input->get('param1');
   $order = $this->input->get('param2');
   $next_order = $this->input->get('param3');
   echo 'current id='. $id.'/n'.' next-id='.$next_id.'/n'.'current order= '.$order.' next-order='.$next_order;
   // echo ' next_id= '.$param1.' this '.$order.'  '.$pre_order;
   // die;
   $next_data = array(
      'display_order' => $next_order
    );

    $this->db->where('id', $id);
    $this->db->update('new_youtube', $next_data);

    $new_data = array(
      'display_order' => $order
    );

    $this->db->where('id', $next_id);
    $this->db->update('new_youtube', $new_data);

    redirect('user/youtube');
  }



}
