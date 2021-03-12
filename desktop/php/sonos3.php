<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('sonos3');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="add">
				<i class="fas fa-plus-circle"></i>
				<br/>
				<span>{{Ajouter}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i>
				<br/>
				<span >{{Configuration}}</span>
			</div>
		</div>
		<legend><i class='fas fa-music'></i> {{Mes Sonos}}</legend>
		<div class="input-group" style="margin:5px;">
			<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic"/>
			<div class="input-group-btn">
				<a id="bt_resetSearch" class="btn roundedRight" style="width:30px"><i class="fas fa-times"></i></a>
			</div>
		</div>
		<div class="eqLogicThumbnailContainer">
			<?php
			foreach ($eqLogics as $eqLogic) {
				$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
				echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
				if ($eqLogic->getConfiguration('model', '') != '') {
					echo '<img src="' . $eqLogic->getImage() . '"/>';
				} else {
					echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
				}
				echo '<br/>';
				echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
				echo '</div>';
			}
			?>
		</div>
	</div>
	
	<div class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-sm btn-default eqLogicAction roundedLeft" data-action="configure"><i class="fa fa-cogs"></i><span class="hidden-xs"> {{Configuration avancée}}</span>
				</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
				</a><a class="btn btn-sm btn-danger eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}
				</a>
			</span>
		</div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a class="eqLogicAction cursor" aria-controls="home" role="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Commandes}}</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<br/>
				<div class="row">
					<div class="col-lg-7">
						<form class="form-horizontal">
							<fieldset>
								<legend><i class="fas fa-wrench"></i> {{Général}}</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Nom du sonos}}</label>
									<div class="col-xs-11 col-sm-7">
										<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
										<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement template}}"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" >{{Objet parent}}</label>
									<div class="col-xs-11 col-sm-7">
										<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
											<option value="">{{Aucun}}</option>
											<?php
											$options = '';
											foreach ((jeeObject::buildTree(null, false)) as $object) {
												$options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
											}
											echo $options;
											?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Options}}</label>
									<div class="col-xs-11 col-sm-7">
										<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
										<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
									</div>
								</div>
								
								<br/>
								<legend><i class="fas fa-cogs"></i> {{Paramètres}}</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Modèle}}</label>
									<div class="col-xs-11 col-sm-7">
										<select type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="model" >
											<option value="PLAY1">Sonos Play 1</option>
											<option value="PLAY3">Sonos Play 3</option>
											<option value="PLAY5">Sonos Play 5</option>
											<option value="CONNECT">Sonos Connect</option>
											<option value="CONNECT:AMP">Sonos Connect AMP</option>
											<option value="PLAYBAR">Sonos Playbar</option>
											<option value="PLAYBASE">Sonos Playbase</option>
											<option value="ARC">Sonos Arc</option>
											<option value="ONE">Sonos One</option>
											<option value="BEAM">Sonos Beam</option>
											<option value="SYMFONISK_LIGHT">Ikea SYMFONISK Lampe</option>
											<option value="SYMFONISK">Ikea SYMFONISK</option>
											<option value="PORT">Sonos port</option>
											<option value="MOVE">Sonos move</option>
											<option value="FIVE">Sonos five</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{IP}}</label>
									<div class="col-xs-11 col-sm-7">
										<input type="text" class="eqLogicAttr configuration form-control" data-l1key="logicalId" placeholder="IP"/>
									</div>
								</div>
								<br/>
							</fieldset>
						</form>
					</div>
					
					<div class="col-lg-5">
						<form class="form-horizontal">
							<fieldset>
								<legend><i class="fas fa-info"></i> {{Informations}}</legend>
								<div class="form-group">
									<label class="col-sm-3"></label>
									<div class="col-sm-7 text-center">
										<img name="icon_visu" src="<?= $plugin->getPathImgIcon(); ?>" id="img_sonosModel" style="max-width:160px;"/>
									</div>
								</div>
							</fieldset>
						</form>
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
