		function getSearchItem(value){
			$.post("getSearchItem.php", {searchItem:value}, function(data){
				$("#searchSuggestions").html(data);
			});
		}