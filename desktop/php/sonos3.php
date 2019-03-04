<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('sonos3');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay" style="padding-left: 25px;">
		<legend><i class="fa fa-cog"></i>  {{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction" data-action="gotoPluginConf" style="text-align: center; background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
				<i class="fa fa-wrench" style="font-size : 5em;color:#767676;"></i>
				<br>
				<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676">{{Configuration}}</span>
			</div>
			<div class="cursor eqLogicAction" data-action="add" style="text-align: center; background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
				<i class="fa fa-plus-circle" style="font-size : 5em;color:#94ca02;"></i>
				<br>
				<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;;color:#94ca02">{{Ajouter}}</span>
			</div>
		</div>
		<div class="eqLogicThumbnailContainer">
			<legend><i class="fa fa-table"></i> {{Mes Sonos}}</legend>
			<input class="form-control" placeholder="{{Rechercher}}" style="margin-bottom:4px;" id="in_searchEqlogic" />
			<?php
			foreach ($eqLogics as $eqLogic) {
				$opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
				echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="text-align: center; background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
				if ($eqLogic->getConfiguration('model', '') != '') {
					echo '<img src="' . $eqLogic->getImage() . '" height="105" width="95" />';
				} else {
					echo '<img src="' . $plugin->getPathImgIcon() . '" height="105" width="95" />';
				}
				echo "<br>";
				echo '<span class="name" style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $eqLogic->getHumanName(true, true) . '</span>';
				echo '</div>';
			}
			?>
		</div>
	</div>
	
	<div class="col-xs-12 eqLogic" style="padding-left: 25px;display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-default eqLogicAction btn-sm roundedLeft" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a class="eqLogicAction cursor" aria-controls="home" role="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipement}}</a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
		</ul>
		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<br/>
				<div class="row">
					<div class="col-xs-6">
						<form class="form-horizontal">
							<fieldset>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Nom du sonos}}</label>
									<div class="col-sm-5">
										<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
										<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement template}}"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" >{{Objet parent}}</label>
									<div class="col-sm-5">
										<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
											<option value="">{{Aucun}}</option>
											<?php
											foreach (object::all() as $object) {
												echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
											}
											?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"></label>
									<div class="col-sm-9">
										<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
										<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Model}}</label>
									<div class="col-sm-5">
										<select type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="model" >
											<option value="PLAY1">Sonos Play 1</option>
											<option value="PLAY3">Sonos Play 3</option>
											<option value="PLAY5">Sonos Play 5</option>
											<option value="CONNECT">Sonos Connect</option>
											<option value="CONNECT:AMP">Sonos Connect AMP</option>
											<option value="PLAYBAR">Sonos Playbar</option>
											<option value="PLAYBASE">Sonos Playbase</option>
											<option value="ONE">Sonos One</option>
											<option value="BEAM">Sonos Beam</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{IP}}</label>
									<div class="col-sm-5">
										<input type="text" class="eqLogicAttr configuration form-control" data-l1key="logicalId" placeholder="IP"/>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="col-xs-6">
						<center>
							<img src="<?php echo $plugin->getPathImgIcon(); ?>" id="img_sonosModel" style="height : 300px;" />
						</center>
					</div>
				</div>
				
			</div>
			<div role="tabpanel" class="tab-pane" id="commandtab">
				<br/>
				<table id="table_cmd" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>{{Nom}}</th><th>{{Action}}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
				
			</div>
		</div>
		
	</div>
</div>

<?php include_file('desktop', 'sonos3', 'js', 'sonos3');?>
<?php include_file('core', 'plugin.template', 'js');?>
