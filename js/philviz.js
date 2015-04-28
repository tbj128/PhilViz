var isSave = false;
var editing_info, editing_zoom, editing_pos;
var show_editbar = false;
var editing_root_node = false;
    
var PhilViz = {
    
    init: function(show_welcome_arg) {
        var settings = {};
        PhilViz.config = {
            show_welcome : show_welcome_arg,
            editbar : $("#editbar"),
            chart_wrapper : $("#chart-content"),
            chart : $("#chart")
        };
        
        $.extend(PhilViz.config, settings);
        PhilViz.setup();
    },
    
    setup: function() {
        // Welcome screen
        if (PhilViz.config.show_welcome) {
            $('#blackout').show();
            $('#welcome').show();
        }
    
        // Chart setup
        PhilViz.config.chart.orgchart({
            width : $(window).width(),
            height : 1000,
            rawDatas : _datas,
            nodeclick : function(evt, info, node) {
                show_editbar = true;
                editing_info = info;
                if (info.pid === null) {
                    editing_root_node = true;
                } else {
                    editing_root_node = false;
                }
                editing_zoom = PhilViz.config.chart.data("orgchart").getZoom();
                editing_pos = PhilViz.config.chart.data("orgchart").getPosition();
            }
        });
        
        PhilViz.config.chart.orgchart("draw", _datas);
        PhilViz.config.chart.width($(window).width());
        PhilViz.config.chart.height($(window).height() - 40);
    
        PhilViz.config.chart_wrapper.width($(window).width());
        PhilViz.config.chart_wrapper.height($(window).height() - 40);
        
        PhilViz.setupClickHandlers();
    }, 
    
    setupClickHandlers: function() {
        $(window).bind('beforeunload', function() {
            if (_datas) {
                if (_datas.length > 1 && !isSave) {
                    return 'Are you sure you want to reload this page? You will lose all your work';
                }
            }
        });
    
        $(document).click(function() {
            if (!show_editbar) {
                PhilViz.config.editbar.fadeOut(50, function() {});
            } else {
                show_editbar = false;
                PhilViz.config.editbar.fadeIn(50, function() {});
                if (editing_root_node) {
                    $('#nav-edit-addparent').show();
                    $('#nav-edit-remove').hide();
                } else {
                    $('#nav-edit-addparent').hide();
                    $('#nav-edit-remove').show();
                }
                PhilViz.config.editbar.show();
            }
        });
        
        $('#nav-new').click(function(e) {
            if (_datas) {                             
                e.preventDefault();
                if (window.confirm("Are you sure you want to start a new diagram? You will also lose any unsaved data.")) {
                    location.reload();
                }
            }
        });
        
        $('#nav-open').click(function() {
            $("#open-file").click();
        });
        
        document.getElementById("open-file").onchange = function() {
            if(!$("#open-file").val()) {
               // No file is uploaded, do not submit.
               // TODO
               return false;
            } else {
                document.getElementById("open-file-form").submit();
            }
        };
        
        $('#nav-save').click(function() {
            var savedJSON = JSON.stringify(_datas);
            isSave = true;
            $("#save-file").val(savedJSON);
            $("#save-file-form").submit();
        });
        
        $('#nav-print').click(function() {
            var svg = $('#chart').find('svg')[0];
            var serializer = new XMLSerializer();
            var str = serializer.serializeToString(svg);
            $("#download-file").val(str);
            $("#download-file-form").submit();
        });

        $('#nav-about').click(function() {
            $('#aboutbox').fadeIn(50, function() {});
            $('#blackout').fadeIn(50, function() {});
        });
        
        $('#about-close').click(function() {
            $('#aboutbox').fadeOut(50, function() {});
            $('#blackout').fadeOut(50, function() {});
        });
    
        // --- Welcome Click Handlers ---
        $('#welcome-close').click(function() {
            $('#welcome').fadeOut(50, function() {});
            $('#blackout').fadeOut(50, function() {});
        });
        
        $('#welcome-new').click(function() {
            $('#welcome').fadeOut(100, function() {});
            $('#blackout').fadeOut(100, function() {});
        });
        
        $('#welcome-open').click(function() {
            $("#open-file").click();
        });
        
        // --- Edit Navbar Click Handlers ---
        $('#nav-edit-edit').click(function() {
            if (editing_info) {
                $('#edit-text').val(editing_info.value);
                PhilViz.showEditEditor();
            } else {
                $('#edit-text').val("");
            }
        });
    
        $('#nav-edit-addparent').click(function() {
            if (editing_info) {
                PhilViz.showAddParentEditor();
            } else {
                $('#parent-add-text').val("");
            }
        });
    
        $('#nav-edit-add').click(function() {
            if (editing_info) {
                PhilViz.showAddEditor();
            } else {
                $('#add-text').val("");
            }
        });
    
        $('#nav-edit-remove').click(function(e) {
            if (editing_info) {
                e.preventDefault();
                if (window.confirm("Are you sure you want to remove this node? You will also lose any child nodes that exist.")) {
                    PhilViz.removeIndices(editing_info.id);
                }
            }
        });
    
                    
        // --- AddBox Button Click Handlers ---
        $('#add-save').click(function() {
            if (editing_info) {
                var new_node = {
                    id : PhilViz.generateUniqueID(),
                    name : "",
                    value : $('#add-text').val(),
                    pid : editing_info.id
                };
                _datas.push(new_node);
                PhilViz.redrawChart();
            } else {
                alert("Error encountered - try again.");
            }
            PhilViz.hideAddEditor();
        });
        $('#add-close').click(function(e) {
            if ($('#add-text').val() === "") {
                e.preventDefault();
                if (window.confirm("Are you sure you want to cancel? You will lose all your changes.")) {
                    PhilViz.hideAddEditor();
                }
            } else {
                PhilViz.hideAddEditor();
            }
        });
        $('#add-cancel').click(function(e) {
            if ($('#edit-text').val() === "") {
                e.preventDefault();
                if (window.confirm("Are you sure you want to cancel? You will lose all your changes.")) {
                    PhilViz.hideAddEditor();
                }
            } else {
                PhilViz.hideAddEditor();
            }
        });
                
        // --- Parent AddBox Button Click Handlers ---
        $('#parent-add-save').click(function() {
            if (editing_info) {
                var new_node = {
                    id : PhilViz.generateUniqueID(),
                    name : "",
                    value : $('#parent-add-text').val(),
                    pid : null
                };
                for (var i = 0; i < _datas.length; i++) {
                    if (_datas[i].id === editing_info.id) {
                        _datas[i].pid = new_node.id;
                        break;
                    }
                }
                _datas.push(new_node);
                PhilViz.redrawChart();
            } else {
                alert("Error encountered - try again.");
            }
            PhilViz.hideAddParentEditor();
        });
        $('#parent-add-close').click(function(e) {
            if ($('#add-text').val() === "") {
                e.preventDefault();
                if (window.confirm("Are you sure you want to cancel? You will lose all your changes.")) {
                    PhilViz.hideAddParentEditor();
                }
            } else {
                PhilViz.hideAddParentEditor();
            }
        });
        $('#parent-add-cancel').click(function(e) {
            if ($('#edit-text').val() === "") {
                e.preventDefault();
                if (window.confirm("Are you sure you want to cancel? You will lose all your changes.")) {
                    PhilViz.hideAddParentEditor();
                }
            } else {
                PhilViz.hideAddParentEditor();
            }
        });
        
        // --- EditBox Button Click Handlers ---
        $('#edit-save').click(function() {                  
            for (var i = 0; i < _datas.length; i++) {
                if (_datas[i].id === editing_info.id) {
                    _datas[i].value = $('#edit-text').val();
                    break;
                }
            }
            PhilViz.redrawChart();
            PhilViz.hideEditEditor();
        });
        $('#edit-close').click(function(e) {
            if ($('#edit-text').val() !== editing_info.value) {
                e.preventDefault();
                if (window.confirm("Are you sure you want to cancel? You will lose all your changes.")) {
                    PhilViz.hideEditEditor();
                }
            } else {
                PhilViz.hideEditEditor();
            }
        });
        $('#edit-cancel').click(function(e) {
            if ($('#edit-text').val() !== editing_info.value) {
                e.preventDefault();
                if (window.confirm("Are you sure you want to cancel? You will lose all your changes.")) {
                    PhilViz.hideEditEditor();
                }
            } else {
                PhilViz.hideEditEditor();
            }
        });
    },
    
    showAddParentEditor: function() {
        $('#blackout').fadeIn(50, function() {});
        $('#parent-addbox').fadeIn(50, function() {});
    },
    
    hideAddParentEditor: function() {
        $('#parent-addbox').fadeOut(50, function() {});
        $('#blackout').fadeOut(50, function() {});
        $('#parent-add-text').val("");
    },
    
    showAddEditor: function() {
        $('#blackout').fadeIn(50, function() {});
        $('#addbox').fadeIn(50, function() {});
    },
    
    hideAddEditor: function() {
        $('#addbox').fadeOut(50, function() {});
        $('#blackout').fadeOut(50, function() {});
        $('#add-text').val("");
    },
    
    showEditEditor: function() {
        $('#blackout').fadeIn(50, function() {});
        $('#editbox').fadeIn(50, function() {});
    },
    
    hideEditEditor: function() {
        $('#editbox').fadeOut(50, function() {});
        $('#blackout').fadeOut(50, function() {});
        $('#edit-text').val("");
    },
    
    findIndicesToRemove: function(idToRemove) {
        if (idToRemove < 0) {
            return;
        }
        
        var indicesToRemove = [];
        for (var i = 0; i < _datas.length; i++) {
            if (_datas[i].pid === idToRemove) {
                indicesToRemove.push(i);
                indicesToRemove.concat(PhilViz.findIndicesToRemove(_datas[i].id));
            }
        }
        return indicesToRemove;
    },
    
    removeIndices: function(idToRemove) {
        if (idToRemove < 0) {
            return;
        }
        
        var isRoot = false;
        var indicesToRemove = PhilViz.findIndicesToRemove(idToRemove);
        for (var i = 0; i < _datas.length; i++) {
            if (_datas[i].id === idToRemove) {
                indicesToRemove.push(i);
                if (_datas[i].pid === null) {
                    isRoot = true;
                }
                break;
            }
        }
        
        if (isRoot) {
            
        } else {
            indicesToRemove.sort(function(a, b){return b - a});
            for (var i = 0; i < indicesToRemove.length; i++) {
                _datas.splice(indicesToRemove[i], 1);
            }
            PhilViz.redrawChart();
        }
    },
    
    redrawChart: function(isNewChart) {
        var newChart = false;
        if (isNewChart) {
            newChart = true;
        }
        
        PhilViz.config.chart.data('orgchart').destory();
        PhilViz.config.chart.orgchart({
            width : $(window).width(),
            height : 1000,
            rawDatas : _datas,
            customPos : true,
            initZoom : editing_zoom,
            initPos : editing_pos,
            isNew : newChart,
            nodeclick : function(evt, info, node) {
                show_editbar = true;
                editing_info = info;
                if (info.pid === null) {
                    editing_root_node = true;
                } else {
                    editing_root_node = false;
                }
                editing_zoom = PhilViz.config.chart.data("orgchart").getZoom();
                editing_pos = PhilViz.config.chart.data("orgchart").getPosition();
            }
        });

        PhilViz.config.chart.orgchart("draw", _datas);
        PhilViz.config.chart.width($(window).width());
        PhilViz.config.chart.height($(window).height() - 40);
    },
    
    generateUniqueID: function() {
        var isUnique = false;
        var newID = 0;
        while (!isUnique) {
            newID = Math.floor((Math.random() * 1000000) + 1);
            var u = true;
            for (var i = 0; i < _datas.length; i++) {
                if (_datas[i].id === newID) {
                    u = false;
                }  
                if (u && i == (_datas.length - 1)) {
                    isUnique = true;
                }
            }
        }
        return newID;
    }
};