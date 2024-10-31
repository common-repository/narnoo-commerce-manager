<div class="panel panel-default">
    <div class="panel-heading">
        <input type="radio" <?php echo $radio_attr; ?>>
        <a data-toggle="collapse" data-parent="#accordion" <?php echo $link_attr; ?>>
            <strong><?php echo $gateway_title; ?></strong>
        </a>
    </div>
    <div id="<?php echo $gateway_id; ?>" class="panel-collapse collapse">
        <div class="panel-body">
            <p><?php echo $gateway_desc; ?></p>
            <div class="gateway_fields"><?php echo $gateway_fields; ?></div>
        </div>
    </div>
</div>