
<table class="table table-sm table-bordered bg-white" style='min-height:80vh'>
	<tr > 
		<td colspan=2>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0" id="breadcrumb">
					<!--
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Library</li>
					-->
				</ol>
			</nav>
		</td>
	</tr>
	<tr style='height:100%'>
		<td style='width:15%'>
			<ul class="fa-ul" id="sidefoldermenu">
				
			
			</ul>
		</td>
		<td ><div class='file-manager-container file-manager-col-view' id='explorer-body' style='height:75vh'></div></td>
	</tr>
</table>

<div id='context-menu-explorer-body' >
	<?php if($this->userinfo['ulvl'] == 1 || $this->userinfo['ulvl'] == 112){ ?>
		<div class='item' id='exp-new-folder'>New Folder</div>
	<?php } ?>
	<div class='item' id='exp-new-file'>Upload File</div>
</div>

<div id='context-menu-folder'>
	<?php if($this->userinfo['ulvl'] == 1 || $this->userinfo['ulvl'] == 112){ ?>
		<div class='item' id='edit-folder'>Edit</div>
		<div class='item' id='delete-folder'>Delete</div>
	<?php } ?>
</div>

<div id='context-menu-file'>
	<div class='item' id='rename-file'>Rename</div>
	<?php if($this->userinfo['ulvl'] == 1 || $this->userinfo['ulvl'] == 112){ ?>
		<div class='item' id='delete-file'>Delete</div>
	<?php } ?>
</div>

<script type='text/javascript'>
	var useraccess = <?= $this->userinfo['ulvl']; ?>;
	var mmenu = <?= json_encode($menudetails); ?>;
	var userlist = <?= json_encode($userlist); ?>;
	var usergrouplist = <?= json_encode($usergrouplist); ?>;
	var boxlist = <?= json_encode($boxlist); ?>;
	var typelist = <?= json_encode($typelist); ?>;
	var deptlist = <?= json_encode($deptlist); ?>;

	boxlist.unshift({"text":"Please Select","id":""});
	typelist.unshift({"text":"Please Select","id":""});
	deptlist.unshift({"text":"Please Select","id":""});
</script>