
<div class="panel panel-default" id="core-link-<?= $t->nbLink ?>">
    <div class="panel-heading">
        <h3 class="panel-title">Link <?= $t->nbLink ?>: </h3>
    </div>
    <div class="form-horizontal">
        <div class="panel-body">
            <div id="message-<?= $t->nbLink ?>"></div>
            <div class="col-sm-3">
                <h5>
                    Side A Switch Port<sup>*</sup>:
                </h5>
                <hr>
                <?= Former::select( 'sp-a-'.$t->nbLink )
                    ->id( 'sp-a-'.$t->nbLink )
                    ->label( '' )
                    ->placeholder( 'Choose a switch port' )
                    ->addClass( 'chzn-select' )
                ?>
                <?= Former::hidden( 'hidden-sp-a-'.$t->nbLink )
                    ->id( 'hidden-sp-a-'.$t->nbLink)
                    ->value( null )
                ?>
            </div>
            <div class="col-sm-3">
                <h5>
                    Side B Switch Port<sup>*</sup>:
                </h5>
                <hr>
                <?= Former::select( 'sp-b-'.$t->nbLink )
                    ->id( 'sp-b-'.$t->nbLink )
                    ->label( '' )
                    ->placeholder( 'Choose a switch port' )
                    ->addClass( 'chzn-select' )
                ?>
                <?= Former::hidden( 'hidden-sp-b-'.$t->nbLink )
                    ->id( 'hidden-sp-b-'.$t->nbLink)
                    ->value( null )
                ?>
            </div>

            <div class="col-sm-1">
                <h5>
                    Enabled:
                </h5>
                <hr>
                <?= Former::checkbox( 'enabled-cl-'.$t->nbLink )
                    ->label( '' )
                    ->unchecked_value( 0 )
                    ->check( $t->enabled )
                ?>
            </div>

            <div class="col-sm-1">
                <?php if( $t->bundleType == \Entities\CoreBundle::TYPE_ECMP ): ?>
                    <h5>
                        BFD:
                    </h5>
                    <hr>
                    <?= Former::checkbox( 'bfd-'.$t->nbLink )
                        ->label( '' )
                        ->unchecked_value( 0 )
                        ->value( 1 )
                    ?>
                <?php endif; ?>
            </div>

            <div class="col-sm-3">
                <?php if( $t->bundleType == \Entities\CoreBundle::TYPE_ECMP ): ?>
                    <h5>
                        Subnet:
                    </h5>
                    <hr>
                    <?= Former::text( 'subnet-'.$t->nbLink )
                        ->label( '' )
                        ->placeholder( '192.0.2.0/30' )
                    ?>
                <?php endif; ?>
            </div>

            <?php if( $t->nbLink > 1 ): ?>
                <div class="col-sm-1">
                    <h5>
                        Action
                    </h5>
                    <hr>
                    <button title="Remove link" id="remove-core-link-<?= $t->nbLink ?>" class="btn btn-default"><i class="glyphicon glyphicon-trash"></i></button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $this->section( 'scripts' ) ?>
<script>
    $(document).ready( function() {
        dropdownChosen();
        event();
    });

    /**
     * initialize the library 'chosen' on the dropdowns
     */
    function dropdownChosen(){
        $( "#s-a-"+<?= $t->nbLink ?> ).select2();
        $( "#sp-a-"+<?= $t->nbLink ?> ).select2();
        $( "#s-b-"+<?= $t->nbLink ?> ).select2();
        $( "#sp-b-"+<?= $t->nbLink ?> ).select2();
    }

    function event(){
        /**
         * on click even delete the core link
         */
        $( document ).on( 'click', "button[id|='remove-core-link']" ,function(e){
            e.preventDefault();
            let id = ( this.id ).substring( 17 );
            let beforeId = id - 1;

            $( "#core-link-" + id ).remove();

            // allow the click on the delete button on the previous core link
            $( "#remove-core-link-"+beforeId ).prop( 'disabled', false );

            $("#nb-core-links").val( beforeId );
            disableDropDown( id - 1 , false );
        });
    }
</script>
<?php $this->append() ?>