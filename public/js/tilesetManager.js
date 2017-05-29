define([
    "jquery-ui"
], function($) {

    var TilesetManager = {},
        Editor;

    var currentMap = 0;
    var aBorrar;
    var recentlySaved = false;

    //Iconos
    var icon_share = "share";
    var icon_export = "file_download";
    var icon_remove = "delete";
    var icon_edit = "mode_edit";

    var default_maps = "default_maps";
    var user_maps = "user_maps";

    TilesetManager.initialize = function(editor) {

        Editor = editor;

        //Registramos el oyente de la lista de mapas
        $(".categories").on("mousedown", "a" ,function(e) {
            //console.log(e.target);
            //console.log(e.currentTarget);
            //Si hicimos click en el icono de visibilidad o eliminar, retornamos
            if ($(e.target).hasClass("secondary-content")) return;
            //Desmarcamos la categoria actual
            $(".categories a").removeClass("active");
            //Marcamos la categoria como la actual
            $(e.currentTarget).addClass("active");
            //Actualizamos el preview
            $(".preview img").attr("src", "/" + $(e.currentTarget).attr("ts-path"));
        });

        //Registramos los oyentes para editar/eliminar
        $(".categories").on("click", "a i", function(event) {
            if ($(event.target).hasClass(icon_edit)) {
                console.log("Mostrar dialogo de edicion");
            } else if ($(event.target).hasClass(icon_remove)) {

                //Obtenemos el mapa que registro el evento
                aBorrar = $(event.target).parent();
                $("#category_delete").modal("open");
            }
        });

        /*
        $("#save_map").on("click", function(){
          Editor.currentState.loadCurrentState();
          $.ajax({ method: "POST", url: "/savemap", data:Editor.currentState.json });
        });
        */

        $("#category_add").on("click", function() {
            $("#dialog_add_category").modal("open");
        });

        //Oyente de confirmacion de borrado
        $("#si_borrar_categoria").on("click", function(event) {
            TilesetManager.deleteCategory(aBorrar);
            TilesetManager.resetLists();
        });

        //Cargamos la lista de categorias
        TilesetManager.loadCategories();

        return this;
    };

    TilesetManager.crearDialog = function() {
        
        //TODO: Getear esto desde el servidor.
        var json = {"3d_rotation":null,"accessibility":null,"account_balance":null,"account_balance_wallet":null,"account_box":null,"account_child":null,"account_circle":null,"add_shopping_cart":null,"alarm":null,"alarm_add":null,"alarm_off":null,"alarm_on":null,"android":null,"announcement":null,"aspect_ratio":null,"assessment":null,"assignment":null,"assignment_ind":null,"assignment_late":null,"assignment_return":null,"assignment_returned":null,"assignment_turned_in":null,"autorenew":null,"backup":null,"book":null,"bookmark":null,"bookmark_outline":null,"bug_report":null,"cached":null,"check_circle":null,"class":null,"credit_card":null,"dashboard":null,"delete":null,"description":null,"dns":null,"done":null,"done_all":null,"event":null,"exit_to_app":null,"explore":null,"extension":null,"face":null,"favorite":null,"favorite_outline":null,"find_in_page":null,"find_replace":null,"flip_to_back":null,"flip_to_front":null,"get_app":null,"grade":null,"group_work":null,"help":null,"highlight_remove":null,"history":null,"home":null,"https":null,"info":null,"info_outline":null,"input":null,"invert_colors":null,"label":null,"label_outline":null,"language":null,"launch":null,"list":null,"lock":null,"lock_open":null,"lock_outline":null,"loyalty":null,"markunread_mailbox":null,"note_add":null,"open_in_browser":null,"open_in_new":null,"open_with":null,"pageview":null,"payment":null,"perm_camera_mic":null,"perm_contact_cal":null,"perm_data_setting":null,"perm_device_info":null,"perm_identity":null,"perm_media":null,"perm_phone_msg":null,"perm_scan_wifi":null,"picture_in_picture":null,"polymer":null,"print":null,"query_builder":null,"question_answer":null,"receipt":null,"redeem":null,"reorder":null,"report_problem":null,"restore":null,"room":null,"schedule":null,"search":null,"settings":null,"settings_applications":null,"settings_backup_restore":null,"settings_bluetooth":null,"settings_cell":null,"settings_display":null,"settings_ethernet":null,"settings_input_antenna":null,"settings_input_component":null,"settings_input_composite":null,"settings_input_hdmi":null,"settings_input_svideo":null,"settings_overscan":null,"settings_phone":null,"settings_power":null,"settings_remote":null,"settings_voice":null,"shop":null,"shop_two":null,"shopping_basket":null,"shopping_cart":null,"speaker_notes":null,"spellcheck":null,"star_rate":null,"stars":null,"store":null,"subject":null,"supervisor_account":null,"swap_horiz":null,"swap_vert":null,"swap_vert_circle":null,"system_update_tv":null,"tab":null,"tab_unselected":null,"theaters":null,"thumb_down":null,"thumb_up":null,"thumbs_up_down":null,"toc":null,"today":null,"track_changes":null,"translate":null,"trending_down":null,"trending_neutral":null,"trending_up":null,"turned_in":null,"turned_in_not":null,"verified_user":null,"view_agenda":null,"view_array":null,"view_carousel":null,"view_column":null,"view_day":null,"view_headline":null,"view_list":null,"view_module":null,"view_quilt":null,"view_stream":null,"view_week":null,"visibility":null,"visibility_off":null,"wallet_giftcard":null,"wallet_membership":null,"wallet_travel":null,"work":null,"error":null,"warning":null,"album":null,"av_timer":null,"closed_caption":null,"equalizer":null,"explicit":null,"fast_forward":null,"fast_rewind":null,"games":null,"hearing":null,"high_quality":null,"loop":null,"mic":null,"mic_none":null,"mic_off":null,"movie":null,"my_library_add":null,"my_library_books":null,"my_library_music":null,"new_releases":null,"not_interested":null,"pause":null,"pause_circle_fill":null,"pause_circle_outline":null,"play_arrow":null,"play_circle_fill":null,"play_circle_outline":null,"play_shopping_bag":null,"playlist_add":null,"queue":null,"queue_music":null,"radio":null,"recent_actors":null,"repeat":null,"repeat_one":null,"replay":null,"shuffle":null,"skip_next":null,"skip_previous":null,"snooze":null,"stop":null,"subtitles":null,"surround_sound":null,"video_collection":null,"videocam":null,"videocam_off":null,"volume_down":null,"volume_mute":null,"volume_off":null,"volume_up":null,"web":null,"business":null,"call":null,"call_end":null,"call_made":null,"call_merge":null,"call_missed":null,"call_received":null,"call_split":null,"chat":null,"clear_all":null,"comment":null,"contacts":null,"dialer_sip":null,"dialpad":null,"dnd_on":null,"email":null,"forum":null,"import_export":null,"invert_colors_off":null,"invert_colors_on":null,"live_help":null,"location_off":null,"location_on":null,"message":null,"messenger":null,"no_sim":null,"phone":null,"portable_wifi_off":null,"quick_contacts_dialer":null,"quick_contacts_mail":null,"ring_volume":null,"stay_current_landscape":null,"stay_current_portrait":null,"stay_primary_landscape":null,"stay_primary_portrait":null,"swap_calls":null,"textsms":null,"voicemail":null,"vpn_key":null,"add":null,"add_box":null,"add_circle":null,"add_circle_outline":null,"archive":null,"backspace":null,"block":null,"clear":null,"content_copy":null,"content_cut":null,"content_paste":null,"create":null,"drafts":null,"filter_list":null,"flag":null,"forward":null,"gesture":null,"inbox":null,"link":null,"mail":null,"markunread":null,"redo":null,"remove":null,"remove_circle":null,"remove_circle_outline":null,"reply":null,"reply_all":null,"report":null,"save":null,"select_all":null,"send":null,"sort":null,"text_format":null,"undo":null,"access_alarm":null,"access_alarms":null,"access_time":null,"add_alarm":null,"airplanemode_off":null,"airplanemode_on":null,"battery_20":null,"battery_30":null,"battery_50":null,"battery_60":null,"battery_80":null,"battery_90":null,"battery_alert":null,"battery_charging_20":null,"battery_charging_30":null,"battery_charging_50":null,"battery_charging_60":null,"battery_charging_80":null,"battery_charging_90":null,"battery_charging_full":null,"battery_full":null,"battery_std":null,"battery_unknown":null,"bluetooth":null,"bluetooth_connected":null,"bluetooth_disabled":null,"bluetooth_searching":null,"brightness_auto":null,"brightness_high":null,"brightness_low":null,"brightness_medium":null,"data_usage":null,"developer_mode":null,"devices":null,"dvr":null,"gps_fixed":null,"gps_not_fixed":null,"gps_off":null,"location_disabled":null,"location_searching":null,"multitrack_audio":null,"network_cell":null,"network_wifi":null,"nfc":null,"now_wallpaper":null,"now_widgets":null,"screen_lock_landscape":null,"screen_lock_portrait":null,"screen_lock_rotation":null,"screen_rotation":null,"sd_storage":null,"settings_system_daydream":null,"signal_cellular_0_bar":null,"signal_cellular_1_bar":null,"signal_cellular_2_bar":null,"signal_cellular_3_bar":null,"signal_cellular_4_bar":null,"signal_cellular_connected_no_internet_0_bar":null,"signal_cellular_connected_no_internet_1_bar":null,"signal_cellular_connected_no_internet_2_bar":null,"signal_cellular_connected_no_internet_3_bar":null,"signal_cellular_connected_no_internet_4_bar":null,"signal_cellular_no_sim":null,"signal_cellular_null":null,"signal_cellular_off":null,"signal_wifi_0_bar":null,"signal_wifi_1_bar":null,"signal_wifi_2_bar":null,"signal_wifi_3_bar":null,"signal_wifi_4_bar":null,"signal_wifi_off":null,"storage":null,"usb":null,"wifi_lock":null,"wifi_tethering":null,"attach_file":null,"attach_money":null,"border_all":null,"border_bottom":null,"border_clear":null,"border_color":null,"border_horizontal":null,"border_inner":null,"border_left":null,"border_outer":null,"border_right":null,"border_style":null,"border_top":null,"border_vertical":null,"format_align_center":null,"format_align_justify":null,"format_align_left":null,"format_align_right":null,"format_bold":null,"format_clear":null,"format_color_fill":null,"format_color_reset":null,"format_color_text":null,"format_indent_decrease":null,"format_indent_increase":null,"format_italic":null,"format_line_spacing":null,"format_list_bulleted":null,"format_list_numbered":null,"format_paint":null,"format_quote":null,"format_size":null,"format_strikethrough":null,"format_textdirection_l_to_r":null,"format_textdirection_r_to_l":null,"format_underline":null,"functions":null,"insert_chart":null,"insert_comment":null,"insert_drive_file":null,"insert_emoticon":null,"insert_invitation":null,"insert_link":null,"insert_photo":null,"merge_type":null,"mode_comment":null,"mode_edit":null,"publish":null,"vertical_align_bottom":null,"vertical_align_center":null,"vertical_align_top":null,"wrap_text":null,"attachment":null,"cloud":null,"cloud_circle":null,"cloud_done":null,"cloud_download":null,"cloud_off":null,"cloud_queue":null,"cloud_upload":null,"file_download":null,"file_upload":null,"folder":null,"folder_open":null,"folder_shared":null,"cast":null,"cast_connected":null,"computer":null,"desktop_mac":null,"desktop_windows":null,"dock":null,"gamepad":null,"headset":null,"headset_mic":null,"keyboard":null,"keyboard_alt":null,"keyboard_arrow_down":null,"keyboard_arrow_left":null,"keyboard_arrow_right":null,"keyboard_arrow_up":null,"keyboard_backspace":null,"keyboard_capslock":null,"keyboard_control":null,"keyboard_hide":null,"keyboard_return":null,"keyboard_tab":null,"keyboard_voice":null,"laptop":null,"laptop_chromebook":null,"laptop_mac":null,"laptop_windows":null,"memory":null,"mouse":null,"phone_android":null,"phone_iphone":null,"phonelink":null,"phonelink_off":null,"security":null,"sim_card":null,"smartphone":null,"speaker":null,"tablet":null,"tablet_android":null,"tablet_mac":null,"tv":null,"watch":null,"add_to_photos":null,"adjust":null,"assistant_photo":null,"audiotrack":null,"blur_circular":null,"blur_linear":null,"blur_off":null,"blur_on":null,"brightness_1":null,"brightness_2":null,"brightness_3":null,"brightness_4":null,"brightness_5":null,"brightness_6":null,"brightness_7":null,"brush":null,"camera":null,"camera_alt":null,"camera_front":null,"camera_rear":null,"camera_roll":null,"center_focus_strong":null,"center_focus_weak":null,"collections":null,"color_lens":null,"colorize":null,"compare":null,"control_point":null,"control_point_duplicate":null,"crop_16_9":null,"crop":null,"crop_3_2":null,"crop_5_4":null,"crop_7_5":null,"crop_din":null,"crop_free":null,"crop_landscape":null,"crop_original":null,"crop_portrait":null,"crop_square":null,"dehaze":null,"details":null,"edit":null,"exposure":null,"exposure_minus_1":null,"exposure_minus_2":null,"exposure_plus_1":null,"exposure_plus_2":null,"exposure_zero":null,"filter_1":null,"filter":null,"filter_2":null,"filter_3":null,"filter_4":null,"filter_5":null,"filter_6":null,"filter_7":null,"filter_8":null,"filter_9":null,"filter_9_plus":null,"filter_b_and_w":null,"filter_center_focus":null,"filter_drama":null,"filter_frames":null,"filter_hdr":null,"filter_none":null,"filter_tilt_shift":null,"filter_vintage":null,"flare":null,"flash_auto":null,"flash_off":null,"flash_on":null,"flip":null,"gradient":null,"grain":null,"grid_off":null,"grid_on":null,"hdr_off":null,"hdr_on":null,"hdr_strong":null,"hdr_weak":null,"healing":null,"image":null,"image_aspect_ratio":null,"iso":null,"landscape":null,"leak_add":null,"leak_remove":null,"lens":null,"looks":null,"looks_3":null,"looks_4":null,"looks_5":null,"looks_6":null,"looks_one":null,"looks_two":null,"loupe":null,"movie_creation":null,"nature":null,"nature_people":null,"navigate_before":null,"navigate_next":null,"palette":null,"panorama":null,"panorama_fisheye":null,"panorama_horizontal":null,"panorama_vertical":null,"panorama_wide_angle":null,"photo":null,"photo_album":null,"photo_camera":null,"photo_library":null,"portrait":null,"remove_red_eye":null,"rotate_left":null,"rotate_right":null,"slideshow":null,"straighten":null,"style":null,"switch_camera":null,"switch_video":null,"tag_faces":null,"texture":null,"timelapse":null,"timer_10":null,"timer":null,"timer_3":null,"timer_auto":null,"timer_off":null,"tonality":null,"transform":null,"tune":null,"wb_auto":null,"wb_cloudy":null,"wb_incandescent":null,"wb_irradescent":null,"wb_sunny":null,"beenhere":null,"directions":null,"directions_bike":null,"directions_bus":null,"directions_car":null,"directions_ferry":null,"directions_subway":null,"directions_train":null,"directions_transit":null,"directions_walk":null,"flight":null,"hotel":null,"layers":null,"layers_clear":null,"local_airport":null,"local_atm":null,"local_attraction":null,"local_bar":null,"local_cafe":null,"local_car_wash":null,"local_convenience_store":null,"local_drink":null,"local_florist":null,"local_gas_station":null,"local_grocery_store":null,"local_hospital":null,"local_hotel":null,"local_laundry_service":null,"local_library":null,"local_mall":null,"local_movies":null,"local_offer":null,"local_parking":null,"local_pharmacy":null,"local_phone":null,"local_pizza":null,"local_play":null,"local_post_office":null,"local_print_shop":null,"local_restaurant":null,"local_see":null,"local_shipping":null,"local_taxi":null,"location_history":null,"map":null,"my_location":null,"navigation":null,"pin_drop":null,"place":null,"rate_review":null,"restaurant_menu":null,"satellite":null,"store_mall_directory":null,"terrain":null,"traffic":null,"apps":null,"arrow_back":null,"arrow_drop_down":null,"arrow_drop_down_circle":null,"arrow_drop_up":null,"arrow_forward":null,"cancel":null,"check":null,"chevron_left":null,"chevron_right":null,"close":null,"expand_less":null,"expand_more":null,"fullscreen":null,"fullscreen_exit":null,"menu":null,"more_horiz":null,"more_vert":null,"refresh":null,"unfold_less":null,"unfold_more":null,"adb":null,"bluetooth_audio":null,"disc_full":null,"dnd_forwardslash":null,"do_not_disturb":null,"drive_eta":null,"event_available":null,"event_busy":null,"event_note":null,"folder_special":null,"mms":null,"more":null,"network_locked":null,"phone_bluetooth_speaker":null,"phone_forwarded":null,"phone_in_talk":null,"phone_locked":null,"phone_missed":null,"phone_paused":null,"play_download":null,"play_install":null,"sd_card":null,"sim_card_alert":null,"sms":null,"sms_failed":null,"sync":null,"sync_disabled":null,"sync_problem":null,"system_update":null,"tap_and_play":null,"time_to_leave":null,"vibration":null,"voice_chat":null,"vpn_lock":null,"cake":null,"domain":null,"group":null,"group_add":null,"location_city":null,"mood":null,"notifications":null,"notifications_none":null,"notifications_off":null,"notifications_on":null,"notifications_paused":null,"pages":null,"party_mode":null,"people":null,"people_outline":null,"person":null,"person_add":null,"person_outline":null,"plus_one":null,"poll":null,"public":null,"school":null,"share":null,"whatshot":null,"check_box":null,"check_box_outline_blank":null,"radio_button_off":null,"radio_button_on":null,"star":null,"star_half":null,"star_outline":null};
        
        //Configuramos los iconos que el admin puede elegir para sus categorias
        $('input.autocomplete').autocomplete({
            data: json,
            /*{
              "Apple": null,
              "Microsoft": null,
              "Google": 'http://placehold.it/250x250'
            },
            */
            limit: 20, // The max amount of results that can be shown at once. Default: Infinity.
            onAutocomplete: function(val) {
              // Callback function when value is autcompleted.
            },
            minLength: 1, // The minimum length of the input for the autocomplete to start. Default: 1.
        });

        //Al seleccionar una imagen la cargamos en la vista previa
        $("#file_input").change(function(){
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#img_preview').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        });

        $("#dialog_add_category").modal({
            dismissible: false, // Modal can be dismissed by clicking outside of the modal
            complete: function() {
                //console.log("Confirmado");
                var img = $("#img_preview").attr("src");
                var height, width;

                var i = new Image(); 
                i.onload = function(){
                    height = i.height;
                    width = i.width;

                    var name = $("#category_name").val();
                    var icon = $("#category_icon").val();
                    var emptyTiles = $("#category_empty_tiles").val();
                    //console.log(img + " " + name + " " + icon + " " + emptyTiles);
                    //Armamos el JSON con la informacion
                    var json = { name:name, path:"img/tilesets/"+name+".png", icon:icon, height:height, width:width, emptyTiles:emptyTiles };
                    //Enviamos la informacion al servidor
                    $.ajax({ method: "POST", url: "/addcategory", data:json, success: function(response){
                            //Almacenamos la imagen del tileset en el servidor
                            $.ajax({ method: "POST", url: "/upload/img/tilesets", data:{str:img, id:name} });
                    }});

                    //Recargamos las categorias
                    TilesetManager.resetLists();
                };
                i.src = img; 
            } // Callback for Modal close
        });

        $("#category_delete").modal({
            dismissible: false, // Modal can be dismissed by clicking outside of the modal
        });
    };

    //Borra el contenido de la interfaz grafica
    TilesetManager.resetLists = function() {
        //Recargamos la lista de mapas para mostrar el nuevo mapa
        $(".categories a").remove();
        //Cargamos la lista de categorias
        TilesetManager.loadCategories();
    };

    TilesetManager.loadCategories = function() {
        //Solicitamos al servidor todos los mapas del Administrador
        $.ajax({ method: "GET", url: "/categories", success: function(response){
            var categorias = response;
            var i;
            //console.log(maps);
            for (i = 0; i < categorias.length; i++) {
                //Las categorias que el Admin marco como desabilitados no se toman en cuenta
                if(categorias[i].habilitado == 0) 
                    return;
                var id = categorias[i].categoriaId;
                var name = categorias[i].name; 
                var path = categorias[i].path; 
                var icon = categorias[i].icon; 
                var width = categorias[i].width; 
                var height = categorias[i].height; 
                var emptyTiles = categorias[i].emptyTiles;
                TilesetManager.addCategory(id, name, path, icon, width, height, emptyTiles);
            }
        }});
        /*
        TilesetManager.addCategory(1, "Terreno", "img/tilesets/terrain.png", "terrain", 256, 192, 0);
        TilesetManager.addCategory(2, "Naturaleza", "img/tilesets/nature.png", "nature", 256, 192, 0);
        TilesetManager.addCategory(3, "Caminos", "img/tilesets/roads.png", "directions", 512, 256, 2);
        TilesetManager.addCategory(4, "Edificios", "img/tilesets/buildings.png", "store", 256, 192, 0);
        */
    };

    //Agrega una nueva capa
    TilesetManager.addCategory = function(id, name, path, icon, width, height, emptyTiles) {

        //Desmarcamos la categoria actual
        $(".categories a").removeClass("active");

        //Creamos el item correspondiente
        var category = $("<a href='#!' class='collection-item active'> <i class='material-icons left'>" + icon + "</i> <i class='category-name'></i> <i class='tileset-size'></i> <i class='secondary-content delete material-icons'>delete</i><i class='secondary-content edit material-icons'>" + icon_edit + "</i></a>");
        
        //Asignamos el id
        category.attr("category-id", id);
        //Asignamos el nombre
        category.children().filter(".category-name").text(name);
        category.children().filter(".tileset-size").text("(" + width + "x" + height + ")");
        //Asignamos los demas parametros
        category.attr("ts-path", path);
        category.attr("ts-width", width);
        category.attr("ts-height", height);
        category.attr("ts-emptyTiles", emptyTiles);

        //Agregamos el item a la lista de categorias
        $(".categories").append(category);

        //Actualizamos el preview
        $(".preview img").attr("src", "/" + path);
    };

    //Elimina el mapa especificado de la base de datos!
    TilesetManager.deleteCategory = function(category) {
        //Obtenemos el id de la categoria y enviamos una solicitud al servidor para eliminarla de la BD
        var category_id = $(category).attr("category-id");
        $.ajax({ method: "GET", url: "/deactivatecategory/" + category_id, success: function(response){
            console.log(response);
            //Eliminamos la capa de la interfaz
            category.remove();
        }});
    };

    return TilesetManager;
});
