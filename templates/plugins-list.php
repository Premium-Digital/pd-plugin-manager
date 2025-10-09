<div class="pd-plugin-manager">
    <h1 class="pd-plugin-manager__heading">PD Plugin Manager</h1>

    <div class="pd-plugin-manager__list">
        <?php foreach ($plugins as $plugin): 
            $status = !$plugin['installed'] ? 'Nie zainstalowany' : ($plugin['active'] ? 'Aktywny' : 'Nieaktywny');
            $status_class = '';
            if (!$plugin['installed']) {
                $status_class = 'pd-plugin-card__status-value--not-installed';
            } elseif ($plugin['active']) {
                $status_class = 'pd-plugin-card__status-value--active';
            } else {
                $status_class = 'pd-plugin-card__status-value--inactive';
            }
        ?>
        <div class="pd-plugin-card">
            <h2 class="pd-plugin-card__title"><?php echo esc_html($plugin['name']); ?></h2>
            <p class="pd-plugin-card__description"><?php echo esc_html($plugin['description'] ?? ''); ?></p>
            
            <p class="pd-plugin-card__status">
                <strong>Status:</strong> 
                <span class="<?php echo $status_class; ?>">
                    <?php echo $status; ?>
                </span>
            </p>

            <div class="pd-plugin-card__buttons">
                <?php if (!$plugin['installed']): ?>
                    <button type="submit" name="plugin_action" data-action="install" class="pd-plugin-card__button pd-plugin-card__button--primary" data-repo-url="<?php echo esc_url($plugin['repo']); ?>">
                        Zainstaluj
                    </button>
                <?php else: ?>
                    <?php if (!$plugin['active']): ?>
                        <button type="submit" name="plugin_action" data-plugin-file="<?php echo esc_attr($plugin['plugin_file']); ?>" data-action="activate" class="pd-plugin-card__button pd-plugin-card__button--primary" data-repo-url="<?php echo esc_url($plugin['repo']); ?>">
                            Włącz
                        </button>
                    <?php else: ?>
                        <button type="submit" name="plugin_action" data-plugin-file="<?php echo esc_attr($plugin['plugin_file']); ?>"  data-action="deactivate" class="pd-plugin-card__button pd-plugin-card__button--secondary">
                            Wyłącz
                        </button>
                    <?php endif; ?>
                    <button type="submit" name="plugin_action" data-plugin-file="<?php echo esc_attr($plugin['plugin_file']); ?>"  data-action="uninstall" class="pd-plugin-card__button pd-plugin-card__button--secondary">
                        Odinstaluj
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
