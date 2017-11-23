<?php $this->layout( 'layouts/ixpv4' );
/** @var object $t */
?>

<?php $this->section( 'title' ) ?>
   <?= $t->wm[ 'name' ]?>
<?php $this->append() ?>

<?php $this->section( 'content' ) ?>

    <?php if( $t->wm ): ?>
        <iframe src="<?= $t->wm[ 'url' ] ?>"
                frameborder="0"
                scrolling="no"
                width="100%"
                height="<?= $t->wm[ 'height' ] ?>"
                style="margin: 0; padding: 0; margin-left: auto; margin-right: auto;"
        ></iframe>
    <?php endif; ?>

    <?php if( is_array( $t->wms ) ): ?>
        <h3>Available Weathermaps</h3>
        <ul>
            <?php foreach( $t->wms as $id => $wp ): ?>
                <li>
                    <a href="<?= route( 'weathermap' , [ 'id' => $id ] ) ?>">
                        <?= $wp[ 'name' ] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

<?php $this->append() ?>