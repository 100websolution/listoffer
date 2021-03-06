<?php use Cake\Routing\Router; $session = $this->request->session();?>
<article class="content item-editor-page">
   <div class="title-block">
      <h3 class="title">
         Add New Banner
         <span class="sparkline bar" data-type="bar"></span>
      </h3>
   </div>
   <?php echo $this->Flash->render() ?>
   <?php echo $this->Form->create($new_bannerSection,['id' => 'login-form', 'novalidate' => 'novalidate', 'enctype'=>'multipart/form-data']); ?>
      <div class="card card-block">
         <div class="form-group row">
            <label class="col-sm-2 form-control-label text-xs-right">
               Title:
            </label>
            <div class="col-sm-10">
               <?php echo $this->Form->input('title', ['required' => false, 'label' => false, 'class' => 'form-control boxed', 'placeholder' => 'Banner Title' ]); ?>
            </div>
         </div>
         <div class="form-group row">
            <label class="col-sm-2 form-control-label text-xs-right">
               Banner Image:
            </label>
            <div class="col-sm-10">
				<?php echo $this->Form->input('image', ['type'=>'file', 'required' => true, 'label' => false, 'class' => 'form-control boxed', 'accept'=>"image/*", 'onchange' => 'image_preview(event)', 'autocomplete' => 'off']); ?>
				<p class="error_msg" style="margin-bottom:0;font-size:13px;"></p>
				<p class="sm_txt_nm" style="margin-bottom:0;">* Please upload jpg, png, jpeg files only, for best view please add above 1073px x 800px image.</p>
				<output id='image_view' style="padding-top:0px;display:block;"></output>
            </div>
         </div>
		<?php
		if( (array_key_exists('change-status',$session->read('permissions.'.strtolower('BannerSections')))) && $session->read('permissions.'.strtolower('BannerSections').'.'.strtolower('change-status'))==1 ){
		?>
		 <div class="form-group row">
			<label class="col-sm-2 form-control-label text-xs-right">Status:</label>
			<div class="col-sm-2">
				<?php echo $this->Form->input('status', ['required' => true, 'label' => false, 'class' => 'form-control boxed', 'placeholder' => 'Status', 'options' => ['A' => 'Active', 'I' => 'Inactive']]); ?>
			</div>
		 </div>
		<?php
		}
		?>
         <div class="form-group row">
            <div class="col-sm-10 col-sm-offset-2">
               <?php echo $this->Form->button('Add',['type' => 'submit','class' => 'btn btn-primary']); ?>&nbsp;
				<a href="<?php echo Router::url('/admin/banner-sections/list-data',true); ?>" class="btn btn-primary">Cancel</a>
            </div>
         </div>
      </div>
   <?php echo $this->Form->end(); ?>
</article>
<script>
function image_preview(evt){
	var files = evt.target.files;
	var num = 0;
	$('.preview_image').remove();
	$('.remove_preview_image').remove();
	
	// Loop through the FileList and render image files as thumbnails.
	for (var i = 0, f; f = files[i]; i++) {
		// Only process image files.
		if (!f.type.match('image.*')) {
			$('.error_msg').html('Please upload valid image file').css({'color':'red'});
			$('#image').val('');
			continue;
		}
		var reader = new FileReader();
		// Closure to capture the file information.
		reader.onload = (function(theFile) {
			return function(e) {
				//Initiate the JavaScript Image object.
                var image = new Image();
				
				//Set the Base64 string return from FileReader as source.
                image.src = e.target.result;
				
				// Render thumbnail.
				var span = document.createElement('span');
				
				var uploaded_file_name = escape(theFile.name);
				var uploaded_file_extension = uploaded_file_name.substring(uploaded_file_name.lastIndexOf('.')+1);
				
				if(uploaded_file_extension=='png' || uploaded_file_extension=='jpg' || uploaded_file_extension=='jpeg' || uploaded_file_extension=='JPEG'){
					$('.error_msg').html('');
					image.onload = function () {
						var height = this.height;
						var width = this.width;
						if (width < 1073 && height < 800) {
							$('.error_msg').html('Image size must be above or equals to 1073px x 800px, your image size is: '+this.width + "px x " + this.height+'px').css({'color':'#ff4444'});
							$('#image').val('');
							return false;
						}else{
							span.innerHTML = 
							[
							'<img class="hide_shown_image preview_image" style="width: 300px; height:150px; border: 1px solid #000; margin: 5px 5px 5px 0; padding: 5px" src="', 
							e.target.result,
							'" title="', escape(theFile.name), 
							'"/><a class="remove_preview_image remove" href="javascript:remove_image();"  style="color:#db0e1e;"><i class="fa fa-trash-o "></i></a>'
							].join('');
						}
					};
				}else{
					$('.error_msg').html('Please upload valid image file').css({'color':'#ff4444'});
					$('#image').val('');
				}
				document.getElementById('image_view').insertBefore(span, null);
			};
		})(f);
		// Read in the image file as a data URL.
		reader.readAsDataURL(f);
	}
}
function remove_image(){
	$('output#image_view span').remove();
	$('#image').val('');
}
</script>