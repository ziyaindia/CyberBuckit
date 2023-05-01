$(document).ready(function() {
	// Your JavaScript code goes here
	var currentPage = 1;
	var pageSize = 8;
	var totalItems = $(".thumbnail-box").length;
	var totalPages = Math.ceil(totalItems / pageSize);
	var maxPagesToShow = 3;
	
	function showPage(page) {
	  $(".thumbnail-box").hide();
	  $(".thumbnail-box").each(function(n) {
		if (n >= pageSize * (page - 1) && n < pageSize * page)
		  $(this).show();
	  });
	}
	
	function updatePagination() {
	  $(".pagination .page").remove();
	  var startPage, endPage;
	  if (totalPages <= maxPagesToShow) {
		startPage = 1;
		endPage = totalPages;
	  } else {
		if (currentPage <= Math.ceil(maxPagesToShow/2)) {
		  startPage = 1;
		  endPage = maxPagesToShow;
		} else if (currentPage + Math.floor(maxPagesToShow/2) >= totalPages) {
		  startPage = totalPages - maxPagesToShow + 1;
		  endPage = totalPages;
		} else {
		  startPage = currentPage - Math.floor(maxPagesToShow/2);
		  endPage = currentPage + Math.floor(maxPagesToShow/2);
		}
	  }
	  for (var i = startPage; i <= endPage; i++) {
		$(".pagination ul").append('<li class="page'+(i==currentPage?' active':'')+'"><a href="#">'+i+'</a></li>');
	  }
	  $(".pagination .prev").toggleClass("disabled", currentPage == 1);
	  $(".pagination .next").toggleClass("disabled", currentPage == totalPages);
	}
	
	showPage(currentPage);
	updatePagination();
	
	$(".pagination").on("click", ".page a", function(event) {
	  event.preventDefault();
	  currentPage = parseInt($(this).text());
	  showPage(currentPage);
	  updatePagination();
	});
	
	$(".pagination #prev").click(function() {
	  if (currentPage > 1) {
		currentPage--;
		showPage(currentPage);
		updatePagination();
	  }
	});
	
	$(".pagination #next").click(function() {
	  if (currentPage < totalPages) {
		currentPage++;
		showPage(currentPage);
		updatePagination();
	  }
	});
	
	  
});




	
