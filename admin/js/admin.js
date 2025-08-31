(function($) {
    'use strict';

    $(document).ready(function() {
        const ajaxUrl = imageOptimization.ajaxUrl;
        const nonces = imageOptimization.nonces;
        const strings = imageOptimization.strings;
        
        // Language selector functionality
        $('#plugin-language-selector').on('change', function() {
            const selectedLocale = $(this).val();
            const currentUrl = window.location.href;
            
            // Show a notice about language change
            const notice = $('<div class="notice notice-info" style="margin: 10px 0; padding: 10px;"></div>');
            if (selectedLocale === 'vi_VN') {
                notice.html('<p>🌍 Switching to Vietnamese interface. Note: To permanently change your WordPress language, go to Settings → General → Site Language.</p>');
            } else {
                notice.html('<p>🌍 Switching to English interface. Note: To permanently change your WordPress language, go to Settings → General → Site Language.</p>');
            }
            
            $('.image-optimization-language-selector').after(notice);
            
            // Store preference in localStorage
            localStorage.setItem('imageOptimizationLanguage', selectedLocale);
            
            // Redirect to WordPress language settings for permanent change
            setTimeout(function() {
                const message = selectedLocale === 'vi_VN' 
                    ? 'To permanently set Vietnamese as your WordPress language, please go to Settings → General and change "Site Language" to "Tiếng Việt".' 
                    : 'To permanently set English as your WordPress language, please go to Settings → General and change "Site Language" to "English (United States)".';
                    
                if (confirm(message + '\n\nWould you like to go to the General Settings page now?')) {
                    window.location.href = 'options-general.php';
                }
            }, 2000);
        });
        
        // Set initial language selection based on current locale or stored preference
        const storedLanguage = localStorage.getItem('imageOptimizationLanguage');
        if (storedLanguage) {
            $('#plugin-language-selector').val(storedLanguage);
        }
        
        // Main dashboard buttons
        const btnScanMain = $('#image-optimization-scan-main');
        
        // Tools section buttons
        const btnScanTools = $('#image-optimization-scan-tools');
        const btnConv = $('#image-optimization-convert');
        const btnRevert = $('#image-optimization-revert-all');
        const btnExpJ = $('#image-optimization-export-json');
        const btnExpC = $('#image-optimization-export-csv');
        
        // htaccess buttons
        const btnAddHtaccess = $('#add-htaccess-rules');
        const btnRemoveHtaccess = $('#remove-htaccess-rules');
        const btnPreviewHtaccess = $('#preview-htaccess-rules');
        
        // Status elements
        const totalEl = $('#total');
        const convEl = $('#converted');
        const pendEl = $('#pending');
        const ignEl = $('#ignored');
        const ignBox = $('#ignored-reasons');
        const ignDim = $('#ign-dim');
        const ignSize = $('#ign-size');
        const ignUnr = $('#ign-unr');
        
        // Progress elements
        const barWrap = $('#image-optimization-progress');
        const bar = $('#image-optimization-bar');
        const barTxt = $('#image-optimization-progress-text');
        
        function enableActions(hasScan, hasPending) {
            btnConv.prop('disabled', !hasPending);
            btnExpJ.prop('disabled', !hasScan); 
            btnExpC.prop('disabled', !hasScan);
        }
        
        // Main scan button (comprehensive optimization with all features)
        btnScanMain.on('click', function() {
            btnScanMain.html('<span class="spinner"></span>' + 'Scanning images...').prop('disabled', true);
            barWrap.show();
            bar.css('width', '0%'); 
            barTxt.text('0% - Scanning images...');
            
            // Step 1: Scan images
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: {
                    action: 'image_optimization_scan',
                    _wpnonce: nonces.scan
                },
                success: function(response) {
                    if (!response.success) { 
                        alert(response.data || 'Scan failed'); 
                        btnScanMain.html('🚀 Start Complete Optimization').prop('disabled', false);
                        return; 
                    }
                    
                    bar.css('width', '25%');
                    barTxt.text('25% - Converting images...');
                    btnScanMain.html('<span class="spinner"></span>' + 'Converting to WebP...');
                    
                    // Step 2: Convert images
                    const pending = response.data.pending;
                    const total = response.data.total;
                    let converted = 0;
                    
                    function convertStep() {
                        $.ajax({
                            url: ajaxUrl,
                            type: 'POST',
                            data: {
                                action: 'image_optimization_convert_batch',
                                _wpnonce: nonces.convert
                            },
                            success: function(data) {
                                if (!data.success) { 
                                    alert(data.data || 'Conversion failed'); 
                                    btnScanMain.html('🚀 Start Complete Optimization').prop('disabled', false);
                                    return; 
                                }
                                
                                converted = data.data.converted_total;
                                const pendingTotal = data.data.pending_total;
                                const conversionProgress = 25 + Math.round((converted / (total || 1)) * 50);
                                bar.css('width', conversionProgress + '%'); 
                                barTxt.text(conversionProgress + '% - Converting images...');
                                
                                if (pendingTotal > 0) { 
                                    setTimeout(convertStep, 250); 
                                } else { 
                                    // Step 3: Configure .htaccess rules automatically
                                    configureHtaccess();
                                }
                            },
                            error: function() {
                                alert('Conversion error occurred'); 
                                btnScanMain.html('🚀 Start Complete Optimization').prop('disabled', false);
                            }
                        });
                    }
                    
                    function configureHtaccess() {
                        bar.css('width', '80%');
                        barTxt.text('80% - Configuring server rules...');
                        btnScanMain.html('<span class="spinner"></span>' + 'Configuring .htaccess...');
                        
                        // Try to add .htaccess rules automatically
                        $.ajax({
                            url: ajaxUrl,
                            type: 'POST',
                            data: {
                                action: 'image_optimization_add_htaccess',
                                _wpnonce: nonces.htaccess || nonces.scan // fallback to scan nonce
                            },
                            success: function(htaccessResponse) {
                                // Complete regardless of .htaccess success
                                completeOptimization(htaccessResponse.success, htaccessResponse.data);
                            },
                            error: function() {
                                // Complete even if .htaccess fails
                                completeOptimization(false, 'Could not configure .htaccess automatically');
                            }
                        });
                    }
                    
                    function completeOptimization(htaccessSuccess, htaccessMessage) {
                        bar.css('width', '100%');
                        barTxt.text('100% - Complete!');
                        btnScanMain.html('✓ Optimization Complete').prop('disabled', false);
                        
                        // Show comprehensive success message
                        let successHtml = '<div class="success-message" style="margin-top: 20px; padding: 20px; background: linear-gradient(135deg, #d4edda 0%, #f8fff8 100%); border: 1px solid #c3e6cb; border-radius: 8px; color: #155724;">';
                        successHtml += '<h3 style="margin-top: 0; color: #155724;">✓ Optimization Complete!</h3>';
                        successHtml += '<p><strong>Images converted:</strong> ' + converted + ' images optimized</p>';
                        
                        if (htaccessSuccess) {
                            successHtml += '<p><strong>✓ .htaccess rules:</strong> Automatically configured</p>';
                        } else {
                            successHtml += '<p><strong>⚠️ .htaccess rules:</strong> ' + htaccessMessage + '</p>';
                        }
                        
                        // LiteSpeed Cache recommendations
                        if (imageOptimization.hasLiteSpeed) {
                            successHtml += '<div style="margin-top: 15px; padding: 15px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 6px; color: #856404;">';
                            successHtml += '<h4 style="margin-top: 0; color: #856404;">🛠️ LiteSpeed Cache Detected - Choose Your Method:</h4>';
                            successHtml += '<p style="margin: 10px 0;"><strong>You now have multiple options to serve WebP/AVIF images:</strong></p>';
                            successHtml += '<ol style="margin: 10px 0; padding-left: 20px;">';
                            successHtml += '<li><strong>Option 1 (LiteSpeed Method):</strong> Enable WebP Replacement in LiteSpeed Cache → Image Optimization</li>';
                            successHtml += '<li><strong>Option 2 (Direct Method):</strong> Use the .htaccess rules that were just configured</li>';
                            successHtml += '<li><strong>Option 3 (PHP Method):</strong> Enable URL replacement in the plugin settings below</li>';
                            successHtml += '</ol>';
                            successHtml += '<p style="margin-bottom: 5px;"><strong>💡 Recommendation:</strong> Choose <em>one method</em> to avoid conflicts. Most users prefer LiteSpeed\'s WebP replacement for better integration.</p>';
                            successHtml += '<p style="margin-bottom: 0; font-size: 13px; color: #6c5a00;"><strong>Note:</strong> If using LiteSpeed WebP replacement, turn OFF the plugin\'s URL replacement setting below to prevent double-processing.</p>';
                            successHtml += '</div>';
                        }
                        
                        successHtml += '<div style="text-align: center; margin-top: 20px;">';
                        successHtml += '<a href="https://wordpress.org/plugins/image-optimization/#reviews" target="_blank" class="button button-primary" style="margin-right: 10px;">⭐ Rate this Plugin</a>';
                        successHtml += '<a href="https://paypal.me/hoquanghien" target="_blank" class="button button-secondary">💖 Support Developer</a>';
                        successHtml += '</div>';
                        successHtml += '</div>';
                        
                        // Show the success message
                        $('.image-optimization-getting-started').after(successHtml);
                        $('#optimization-success').show();
                        
                        // Auto-refresh after a delay to show updated stats
                        setTimeout(function() {
                            location.reload();
                        }, 5000);
                    }
                    
                    if (pending > 0) {
                        convertStep();
                    } else {
                        // No images to convert, just configure .htaccess
                        configureHtaccess();
                    }
                },
                error: function() {
                    alert('Scan error occurred'); 
                    btnScanMain.html('🚀 Start Complete Optimization').prop('disabled', false);
                }
            });
        });
        
        // Tools scan button (only scans, doesn't convert)
        btnScanTools.on('click', function() {
            btnScanTools.prop('disabled', true);
            
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: {
                    action: 'image_optimization_scan',
                    _wpnonce: nonces.scan
                },
                success: function(response) {
                    btnScanTools.prop('disabled', false);
                    if (!response.success) { 
                        alert(response.data || strings.scanFailed); 
                        return; 
                    }
                    
                    const s = response.data;
                    totalEl.text(s.total);
                    convEl.text(s.converted);
                    pendEl.text(s.pending);
                    ignEl.text(s.ignored.total);
                    ignDim.text(s.ignored.too_small_dimensions);
                    ignSize.text(s.ignored.too_small_filesize);
                    ignUnr.text(s.ignored.unreadable);
                    
                    if (s.ignored.total > 0) {
                        ignBox.show();
                    } else {
                        ignBox.hide();
                    }
                    
                    enableActions(true, s.pending > 0);
                },
                error: function() {
                    btnScanTools.prop('disabled', false); 
                    alert(strings.scanError); 
                }
            });
        });
        
        // Convert button in tools section
        btnConv.on('click', function() {
            let converted = 0, pending = 0, total = 0;
            barWrap.show();
            bar.css('width', '0%'); 
            barTxt.text('0%');
            btnConv.prop('disabled', true);
            
            function step() {
                $.ajax({
                    url: ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'image_optimization_convert_batch',
                        _wpnonce: nonces.convert
                    },
                    success: function(response) {
                        if (!response.success) { 
                            alert(response.data || strings.convertFailed); 
                            enableActions(true, true); 
                            return; 
                        }
                        
                        const s = response.data;
                        converted = s.converted_total; 
                        pending = s.pending_total; 
                        total = s.total;
                        const done = Math.round((converted / (converted + pending || 1)) * 100);
                        bar.css('width', done + '%'); 
                        barTxt.text(done + '%');
                        
                        totalEl.text(total); 
                        convEl.text(converted); 
                        pendEl.text(pending);
                        ignEl.text(s.ignored_total);
                        ignDim.text(s.ignored_breakdown.too_small_dimensions);
                        ignSize.text(s.ignored_breakdown.too_small_filesize);
                        ignUnr.text(s.ignored_breakdown.unreadable);
                        
                        if (s.ignored_total > 0) {
                            ignBox.show();
                        } else {
                            ignBox.hide();
                        }
                        
                        if (pending > 0) { 
                            setTimeout(step, 250); 
                        } else { 
                            enableActions(true, false); 
                            alert(strings.done); 
                            location.reload(); // Refresh to see updated analytics
                        }
                    },
                    error: function() {
                        alert(strings.batchError); 
                        enableActions(true, true); 
                    }
                });
            }
            step();
        });
        
        // Export buttons
        btnExpJ.on('click', function() {
            window.location = 'admin-post.php?action=image_optimization_export_json&_wpnonce=' + nonces.export;
        });
        
        btnExpC.on('click', function() {
            window.location = 'admin-post.php?action=image_optimization_export_csv&_wpnonce=' + nonces.export;
        });
        
        // Revert all WebP files
        btnRevert.on('click', function() {
            if (!confirm(strings.revertConfirm)) {
                return;
            }
            
            btnRevert.prop('disabled', true);
            
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: {
                    action: 'image_optimization_revert_all',
                    _wpnonce: nonces.revert
                },
                success: function(response) {
                    btnRevert.prop('disabled', false);
                    if (response.success) {
                        alert(strings.revertCompleted);
                        location.reload();
                    } else {
                        alert(response.data || strings.revertFailed);
                    }
                },
                error: function() {
                    btnRevert.prop('disabled', false);
                    alert(strings.revertFailed);
                }
            });
        });
        
        // .htaccess management
        btnAddHtaccess.on('click', function() {
            btnAddHtaccess.prop('disabled', true);
            
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: {
                    action: 'image_optimization_add_htaccess',
                    _wpnonce: nonces.htaccess
                },
                success: function(response) {
                    btnAddHtaccess.prop('disabled', false);
                    if (response.success) {
                        alert(strings.htaccessAdded);
                        location.reload();
                    } else {
                        alert(response.data || strings.htaccessFailed);
                    }
                },
                error: function() {
                    btnAddHtaccess.prop('disabled', false);
                    alert(strings.htaccessFailed);
                }
            });
        });
        
        btnRemoveHtaccess.on('click', function() {
            btnRemoveHtaccess.prop('disabled', true);
            
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: {
                    action: 'image_optimization_remove_htaccess',
                    _wpnonce: nonces.htaccess
                },
                success: function(response) {
                    btnRemoveHtaccess.prop('disabled', false);
                    if (response.success) {
                        alert(strings.htaccessRemoved);
                        location.reload();
                    } else {
                        alert(response.data || strings.htaccessFailed);
                    }
                },
                error: function() {
                    btnRemoveHtaccess.prop('disabled', false);
                    alert(strings.htaccessFailed);
                }
            });
        });
        
        btnPreviewHtaccess.on('click', function() {
            $('#htaccess-preview').toggle();
        });
    });
})(jQuery);