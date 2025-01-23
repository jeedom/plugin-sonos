<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
/** @var plugin */
$plugin = plugin::byId('sonos3');
sendVarToJS('eqType', $plugin->getId());
/** @var sonos3[] */
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="sync">
				<i class="fas fa-sync"></i>
				<br />
				<span>{{Synchroniser}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i>
				<br />
				<span>{{Configuration}}</span>
			</div>
		</div>
		<legend><i class='fas fa-music'></i> {{Mes Sonos}}</legend>
		<?php
		if (count($eqLogics) == 0) {
			echo '<br><div class="text-center" style="font-size:1.2em;font-weight:bold;">{{Aucun équipement Sonos trouvé, avez-vous démarré le démon?}}</div>';
		} else {
			echo '<div class="input-group" style="margin:5px;">';
			echo '<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic">';
			echo '<div class="input-group-btn">';
			echo '<a id="bt_resetSearch" class="btn" style="width:30px"><i class="fas fa-times"></i></a>';
			echo '<a class="btn roundedRight hidden" id="bt_pluginDisplayAsTable" data-coreSupport="1" data-state="0"><i class="fas fa-grip-lines"></i></a>';
			echo '</div>';
			echo '</div>';
			echo '<div class="eqLogicThumbnailContainer">';
			foreach ($eqLogics as $eqLogic) {
				$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
				echo '<div class="eqLogicDisplayCard cursor ' . $opacity . '" data-eqLogic_id="' . $eqLogic->getId() . '">';
				echo '<img src="' . $eqLogic->getImage() . '"/>';
				echo '<br>';
				echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
				echo '<span class="hiddenAsCard displayTableRight hidden">';
				if ($eqLogic->getConfiguration('ip_address') != '') {
					echo '<span class="label label-info">' . $eqLogic->getConfiguration('ip_address') . '</span>';
				}
				echo ($eqLogic->getIsVisible() == 1) ? '<i class="fas fa-eye" title="{{Equipement visible}}"></i>' : '<i class="fas fa-eye-slash" title="{{Equipement non visible}}"></i>';
				echo '</span>';
				echo '</div>';
			}
			echo '</div>';
		}
		?>
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
			<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list"></i> {{Commandes}}</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<form class="form-horizontal">
					<fieldset>
						<div class="col-lg-6">
							<legend><i class="fas fa-wrench"></i> {{Paramètres généraux}}</legend>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Nom du Sonos}}</label>
								<div class="col-sm-6">
									<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
									<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement Sonos}}" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Objet parent}}</label>
								<div class="col-sm-6">
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
								<label class="col-sm-4 control-label">{{Catégorie}}</label>
								<div class="col-sm-6">
									<?php
									foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
										echo '<label class="checkbox-inline">';
										echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" >' . $value['name'];
										echo '</label>';
									}
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Options}}</label>
								<div class="col-sm-6">
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked />{{Activer}}</label>
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked />{{Visible}}</label>
								</div>
							</div>

							<legend><i class="fas fa-cogs"></i> {{Paramètres spécifiques}}</legend>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Utiliser la tuile préconfigurée}}</label>
								<div class="col-sm-2">
									<input type="checkbox" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="customWidget" />
								</div>
							</div>
						</div>

						<div class="col-lg-6">
							<legend><i class="fas fa-info"></i> {{Informations}}</legend>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Modèle}}</label>
								<div class="col-sm-6">
									<span class="label label-default eqLogicAttr" data-l1key="configuration" data-l2key="model_name"></span>/<span class="label label-default eqLogicAttr" data-l1key="configuration" data-l2key="model_number"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4"></label>
								<div class="col-sm-6 text-center">
									<img name="icon_visu" src="<?= $plugin->getPathImgIcon(); ?>" id="img_sonosModel" style="max-width:160px;" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Version}}</label>
								<div class="col-sm-6">
									<span class="label label-default eqLogicAttr" data-l1key="configuration" data-l2key="display_version"></span>/<span class="label label-default eqLogicAttr" data-l1key="configuration" data-l2key="software_version"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Version matériel}}</label>
								<div class="col-sm-6">
									<span class="label label-default eqLogicAttr" data-l1key="configuration" data-l2key="hardware_version"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Numéro de série}}</label>
								<div class="col-sm-6">
									<span class="label label-default eqLogicAttr" data-l1key="configuration" data-l2key="serial_number"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{UID}}</label>
								<div class="col-sm-6">
									<span class="label label-default eqLogicAttr" data-l1key="configuration" data-l2key="uid"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Adresse MAC}}</label>
								<div class="col-sm-6">
									<span class="label label-default eqLogicAttr" data-l1key="configuration" data-l2key="mac_address"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Adresse IP}}</label>
								<div class="col-sm-6">
									<span class="label label-default eqLogicAttr" data-l1key="configuration" data-l2key="ip_address"></span>
								</div>
							</div>
						</div>
					</fieldset>
				</form>
			</div>

			<div role="tabpanel" class="tab-pane" id="commandtab">
				<table id="table_cmd" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th class="hidden-xs" style="min-width:50px;width:70px;">ID</th>
							<th style="min-width:220px;width:300px;">{{Nom}}</th>
							<th style="min-width:140px;width:200px;">{{Type}}</th>
							<th style="min-width:260px;width:280px;">{{Options}}</th>
							<th>{{Etat}}</th>
							<th style="min-width:150px;width:250px;">{{Actions}}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>

		</div>
	</div>
</div>

<?php include_file('desktop', 'sonos3', 'js', 'sonos3'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>