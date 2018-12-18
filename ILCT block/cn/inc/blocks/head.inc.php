    <noscript>
      <div class="alert alert-error">
        <i class="icon-warning-sign"></i> 必须启用JavaScript才能让这个web钱包正常工作！
      </div>
    </noscript>

    <div class="well warning_well<?php if ($rpc_debug == false || empty($rpc_error)) { echo ' no_display'; } ?>">
      <span id="error_text"><?php if ($rpc_debug) { safe_echo("RPC ERROR: $rpc_error"); } ?></span>
    </div>
