<?php
/**
 * Alerta de éxito personalizada de Agendit.
 *
 * @var string $message
 */
?>

<div class="agendit-alert agendit-alert-success" role="alert">

    <div class="agendit-alert-icon">
        <iconify-icon icon="solar:check-circle-bold"></iconify-icon>
    </div>

    <div class="agendit-alert-content">

        <strong>¡Listo!</strong>

        <span>
            <?= h($message) ?>
        </span>

    </div>

    <button
        type="button"
        class="agendit-alert-close"
        onclick="this.closest('.agendit-alert').remove()"
        aria-label="Cerrar"
    >
        &times;
    </button>

</div>