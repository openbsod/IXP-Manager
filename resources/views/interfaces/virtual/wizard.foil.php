<?php
/** @var Foil\Template\Template $t */
$this->layout( 'layouts/ixpv4' );
?>

<?php $this->section( 'title' ) ?>
    <a href="<?= route( 'interfaces/virtual/list' )?>">Virtual Interfaces</a>
<?php $this->append() ?>

<?php $this->section( 'page-header-postamble' ) ?>
    <li>Add Interface Wizard</li>
<?php $this->append() ?>

<?php $this->section( 'page-header-preamble' ) ?>
<li class="pull-right">
    <div class=" btn-group btn-group-xs" role="group">
        <a type="button" class="btn btn-default" href="<?= action( 'Interfaces\VirtualInterfaceController@list' )?>" title="list">
            <span class="glyphicon glyphicon-th-list"></span>
        </a>
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-plus"></i> <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right">
            <li>
                <a id="" href="<?= action( 'Interfaces\VirtualInterfaceController@wizard' )?>" >
                    Add Interface Wizard...
                </a>
            </li>
            <li>
                <a id="" href="<?= action( 'Interfaces\VirtualInterfaceController@add' )?>" >
                    Virtual Interface Only...
                </a>
            </li>
        </ul>
    </div>
</li>
<?php $this->append() ?>

<?php $this->section('content') ?>

<?= $t->alerts() ?>

<?= Former::open()->method( 'POST' )
    ->action( route( 'interfaces/virtual/wizard-save' ) )
    ->customWidthClass( 'col-sm-7' )
?>

<div id="div-well" class="well collapse"> <?php /* collapse as we change CSS is JS and will reveal it afterwards */ ?>

    <div class="row">

        <div class="col-sm-4">
            <h3>
                Virtual Interface Settings
            </h3>
            <hr>
            <?= Former::select( 'cust' )
                ->label( 'Customer' )
                ->fromQuery( $t->custs, 'name' )
                ->placeholder( 'Choose a Customer' )
                ->addClass( 'chzn-select' )
                ->disabled( $t->selectedCust ? true : false )
                ->blockHelp( '' );
            ?>

            <?php if( $t->selectedCust ): ?>
                <?= Former::hidden( 'cust' )
                    ->value( $t->selectedCust->getId() )
                ?>
            <?php endif; ?>

            <?= Former::select( 'vlan' )
                ->label( 'Vlan' )
                ->fromQuery( $t->vlans, 'name' )
                ->placeholder( 'Choose a Vlan' )
                ->addClass( 'chzn-select' )
                ->blockHelp( '' );
            ?>

            <?= Former::checkbox( 'trunk' )
                ->label('&nbsp;')
                ->text( 'Use 802.1q framing' )
                ->blockHelp( 'Indicates if this port should be configured for 802.1q framing / tagged packets.' )
                ->check( false )
            ?>

            <?= Former::checkbox( 'ipv6-enabled' )
                ->label('&nbsp;')
                ->text( 'IPv6 Enabled' )
                ->blockHelp( ' ' )
            ?>

            <?= Former::checkbox( 'ipv4-enabled' )
                ->label('&nbsp;')
                ->text( 'IPv4 Enabled' )
                ->blockHelp( ' ' )
            ?>

        </div>

        <div class="col-sm-4">
            <h3>
                Physical Interface Settings
            </h3>
            <hr>
            <div id="fanout-box" class="collapse">
                <?= Former::checkbox( 'fanout' )
                    ->label('&nbsp;')
                    ->text( 'Associate a fanout port' )
                    ->blockHelp( ' ' )
                ?>
            </div>
            <?= Former::select( 'switch' )
                ->label( 'Switch' )
                ->fromQuery( $t->pi_switches, 'name' )
                ->placeholder( 'Choose a switch' )
                ->addClass( 'chzn-select' )
                ->blockHelp( '' );
            ?>

            <?= Former::select( 'switch-port' )
                ->label( 'Switch Port' )
                ->placeholder( 'Choose a switch port' )
                ->addClass( 'chzn-select' )
                ->blockHelp( 'Shows ports that have a type of <em>Peering</em> or <em>Unknown</em> and have not been associated with any other customer / virtual interface.' );
            ?>

            <?= Former::hidden( 'original-switch-port')
                ->id('original-switch-port')
                ->forceValue( old('switch-port') )
            ?>

            <?= Former::select( 'status' )
                ->label( 'Status' )
                ->fromQuery( $t->pi_states, 'name' )
                ->placeholder( 'Choose a status' )
                ->addClass( 'chzn-select' )
                ->blockHelp( 'Only virtual interfaces with at least one <em>connected</em> interface will be considered for monitoring / route server configuration, etc.' );
            ?>

            <?= Former::select( 'speed' )
                ->label( 'Speed' )
                ->fromQuery( $t->pi_speeds, 'name' )
                ->placeholder( 'Choose a speed' )
                ->addClass( 'chzn-select' )
                ->blockHelp( '' );
            ?>

            <?= Former::select( 'duplex' )
                ->label( 'Duplex' )
                ->fromQuery( $t->pi_duplexes, 'name' )
                ->placeholder( 'Choose Duplex' )
                ->addClass( 'chzn-select' )
                ->value(1)
                ->blockHelp( '' );
            ?>
        </div>

        <div class="col-sm-4">
            <h3>
                General VLAN Settings
            </h3>
            <hr>
            <?= Former::number( 'maxbgpprefix' )
                ->label( 'Max BGP Prefixes' )
                ->blockHelp( 'Setting this will override the overall customer setting. Leave blank to use the overall setting.' );
            ?>

            <?= Former::checkbox( 'irrdbfilter' )
                ->label('&nbsp')
                ->text( 'Apply IRRDB Filtering' )
                ->blockHelp( "<strong>Strongly recommended!</strong> Filter routes learned on route servers based on the customer's IRRDB entries." )
                ->check( true )
            ?>

            <?= Former::checkbox( 'mcastenabled' )
                ->label('&nbsp')
                ->text( 'Multicast Enabled' )
                ->blockHelp( 'Indicates if this port should be configured to allow multicast.' )
            ?>

            <?= Former::checkbox( 'rsclient' )
                ->label('&nbsp;')
                ->text( 'Route Server Client' )
                ->blockHelp( 'Indicates if IXP Manager should configure route server BGP sessions for this interface.' )
            ?>

            <?= Former::checkbox( 'as112client' )
                ->label( '&nbsp;' )
                ->text( 'AS112 Client' )
                ->blockHelp( 'Indicates if IXP Manager should configure AS112 BGP sessions for this interface.' )

            ?>
        </div>
    </div>
</div>

<br/>

<div class="row">

    <div id='ipv6-area' class="col-sm-4" style="<?= old( 'ipv6-enabled' ) || Former::checkbox( 'ipv6-enabled')->getValue() !== null ?: 'display: none' ?>">
        <?= $t->insert( 'interfaces/common/vli/ipv6.foil.php' ) ?>
    </div>

    <div id='ipv4-area' class="col-sm-4" style="<?= old( 'ipv4-enabled' ) || Former::checkbox( 'ipv4-enabled')->getValue() !== null ?: 'display: none' ?>">
        <?= $t->insert( 'interfaces/common/vli/ipv4.foil.php' ) ?>
    </div>

</div>

<div class="row">
    <div class="span12">

        <br><br>

        <?=Former::actions(
            Former::primary_submit( 'Save Changes' ),
            Former::default_link( 'Cancel' )->href( route( 'interfaces/virtual/list' ) ),
            Former::success_button( 'Inline Help' )->id( 'help-btn' ),
            Former::info_link( 'External Documentation &Gt;' )->href( 'http://docs.ixpmanager.org/usage/interfaces/' )->target( '_blank' )->id( 'help-btn' )
        )->id('btn-group');?>

    </div>
</div>

<?= Former::close() ?>


<?php $this->append() ?>

<?php $this->section( 'scripts' ) ?>
    <?= $t->insert( 'interfaces/common/js/interface-functions' ); ?>
    <?= $t->insert( 'interfaces/common/js/pi-form-logic' ); ?>
    <?= $t->insert( 'interfaces/common/js/vli-form-logic' ); ?>
    <?= $t->insert( 'interfaces/virtual/js/wizard' ); ?>
<?php $this->append() ?>

