<?php

	$tbg_response->addBreadcrumb(__('Planning'), null, tbg_get_breadcrumblinks('project_summary', $selected_project));
	$tbg_response->setTitle(__('"%project_name" project planning', array('%project_name' => $selected_project->getName())));

?>
		<?php include_template('project/projectheader', array('selected_project' => $selected_project)); ?>
		<?php include_template('project/projectinfosidebar', array('selected_project' => $selected_project, 'table_id' => 'project_planning')); ?>
		<div class="planning_container" id="planning_container">
			<h3>
				<?php if ($tbg_user->canManageProjectReleases($selected_project)): ?>
					<a class="dropper button button-silver" id="more_actions_milestones_button"><?php echo __('Actions'); ?></a>
					<ul class="simple_list rounded_box white shadowed more_actions_dropdown dropdown_box popup_box">
						<li><?php echo javascript_link_tag(__('Add new milestone'), array('onclick' => "TBG.Main.Helpers.Backdrop.show('".make_url('get_partial_for_backdrop', array('key' => 'milestone', 'project_id' => $selected_project->getId()))."');TBG.Main.Helpers.toggler(jQuery('#more_actions_milestones_button'));")); ?></li>
						<li><?php echo javascript_link_tag(__('Toggle hidden milestones'), array('onclick' => "$('planning_container').toggleClassName('show_unavailable');TBG.Main.Helpers.toggler(jQuery('#more_actions_milestones_button'));")); ?></li>
						<li><?php echo javascript_link_tag(__('Configure columns'), array('onclick' => "$('planning_column_settings_container').toggle();TBG.Main.Helpers.toggler(jQuery('#more_actions_milestones_button'));")); ?></li>
					</ul>
				<?php endif; ?>
				<?php echo __('Project milestones'); ?>
			</h3>
			<?php if ($tbg_user->canManageProjectReleases($selected_project)): ?>
				<table cellpadding=0 cellspacing=0 style="display: none; margin-left: 5px; width: 300px;" id="sprint_add_indicator">
					<tr>
						<td style="width: 20px; padding: 2px;"><?php echo image_tag('spinning_20.gif'); ?></td>
						<td style="padding: 0px; text-align: left;"><?php echo __('Adding sprint, please wait'); ?>...</td>
					</tr>
				</table>
			<?php endif; ?>
			<div class="faded_out" style="margin-top: 10px; font-size: 13px;<?php if (count($selected_project->getMilestones()) > 0): ?> display: none;<?php endif; ?>" id="no_milestones"><?php echo __('No milestones have been created yet.'); ?></div>
			<div id="search_results">
				<?php if ($tbg_user->canEditProjectDetails($selected_project)) include_template('search/bulkactions', array('mode' => 'top')); ?>
				<div id="milestone_list">
					<?php foreach ($selected_project->getMilestones() as $milestone): ?>
						<?php include_template('milestonebox', array('milestone' => $milestone)); ?>
					<?php endforeach; ?>
					<?php include_template('milestonebox', array('milestone' => $unassigned_milestone)); ?>
				</div>
				<?php if ($tbg_user->canEditProjectDetails($selected_project)) include_template('search/bulkactions', array('mode' => 'bottom')); ?>
			</div>
		</div>
		<?php if ($tbg_user->canAssignScrumUserStories($selected_project)): ?>
			<script type="text/javascript">
				<?php foreach ($selected_project->getMilestonesForRoadmap() as $milestone): ?>
					Droppables.add('milestone_<?php echo $milestone->getID(); ?>', { hoverclass: 'highlighted', onDrop: function (dragged, dropped, event) { TBG.Project.Planning.assign('<?php echo make_url('project_scrum_assign_story', array('project_key' => $selected_project->getKey())); ?>', dragged, dropped)}});
				<?php endforeach; ?>
				Droppables.add('milestone_0', { hoverclass: 'highlighted', onDrop: function (dragged, dropped, event) { TBG.Project.Planning.assign('<?php echo make_url('project_scrum_assign_story', array('project_key' => $selected_project->getKey())); ?>', dragged, dropped)}});
			</script>
		<?php endif; ?>
	</td>
	</tr>
</table>
<script type="text/javascript">
(function($) {
	$(".milestone_box")
		.on("click", ".nosort input[type='checkbox']", TBG.Search.toggleCheckboxes)
		.on("click", ".milestone_issue_row input[type='checkbox']", TBG.Search.toggleCheckbox);
})(jQuery);
</script>
<?php include_template('project/projectplanningsettings', array('selected_project' => $selected_project)); ?>
