var strRoot = "/roemedia/beerfest/";
var App = {}

function getClass(strType) {
    var strClass = window[strType]
    return new strClass();
}

$(window).on("load", function () {
    App = new Application();
    App.initialize();

    $(".weighting").on("change", function (e) {
        var $objClass = getClass($(this).closest("form").attr("name"));
        $objClass.handle_weighting(e, $(this));
    });

    $("#result-chart").on("click", function() {
        $(this).hide();
        $("#overlay").remove();
    });

    $(document).on("click", "#logout", function (e) {
        e.preventDefault();
        App.logout($(this).attr("href"));
    });

    $("#page-panel-button").on("click", function () {
        $("#page-panel").toggle();
    });

    $("#page-content").on("click", function() {
        $("#page-panel").hide();
    });

    $("select.toggle").on("change", function () {
        try {
            var strType = $(this).attr("data-module");
            var strId = $(this).attr("data-id");
            var $objClass = getClass(strType);
            $objClass.toggle($(this), strId, function (response) {

            });
        } catch (e) {

        }
    });

    $(".status").on("click", function (e) {
        var strType = $(this).closest("table").attr("data-module");
        var strId = $(this).closest("tr").attr("data-id");
        var $objClass = getClass(strType);
        $objClass.toggle($(this), strId);
    });

    $(".view").on("dblclick", function () {
        try {
            var strType = $(this).closest("table").attr("data-module");
            var strId = $(this).closest("tr").attr("data-id");
            var $objClass = getClass(strType);
            $objClass.view(strId);
        } catch (e) {
            console.log('Class "' + strType + '" does not exist');
        }
    });

    $(".button-view").on("click", function () {
        try {
            var strType = $(this).closest("table").attr("data-module");
            var strId = $(this).closest("tr").attr("data-id");
            var $objClass = getClass(strType);
            $objClass.view(strId);
        } catch (e) {
            console.log('Class "' + strType + '" does not exist');
        }
    });

    $(".add").on("click", function () {
        var $objClass = getClass($(this).attr("data-module"));
        var strId = $(this).attr("data-id");
        $objClass.add(strId);
    });

    $(document).on("click", ".edit", function () {
        try {
            var strType = $(this).closest("table").attr("data-module");
            if(strType == undefined)
            {
                strType = $(this).attr("data-module");
            }
            var strId = $(this).attr("data-id");
            var $objClass = getClass(strType);
            $objClass.edit(strId);
        } catch (e) {

        }
    });

    $(document).on("click", ".delete", function () {
        var blnConfirm = confirm("Confirm delete");
        if (blnConfirm) {
            var strType = $(this).closest("table").attr("data-module");
            var strId = $(this).attr("data-id");
            var $objClass = getClass(strType);
            var $objRow = $(this).closest("tr");
            $objClass.delete(strId, function() {
                $objRow.fadeOut("slow", function () {
                    $objRow.remove();
                });
            });
        }
    });

    // Form submit
    $("form").on("submit", function (e) {
        e.preventDefault();
        var $objForm = $(this);
        $objForm.find("#form-error").remove();
        var $objValues = {};
        $.each($objForm.find("input, select, textarea"), function () {
            var $objInput = $(this);
            var strValue = $objInput.val();
            if ($objInput.attr("name") !== undefined) {
                if ($objInput.attr("name").indexOf("[") > 0) {
                    var values = $objInput.attr("name").split("[");
                    var strField = values[0];
                    var values2 = values[1].split("]");
                    var strField2 = values2[0];
                    if(!$objValues[strField]) {
                        $objValues[strField] = {};
                    }
                    $objValues[strField][strField2] = strValue;
                }
                else {
                    $objValues[$objInput.attr("name")] = strValue;
                }
            }
        });

        $.ajax({
            type: "POST",
            url: $objForm.attr("action"),
            dataType: "json",
            data: { data : JSON.stringify($objValues)},
            success: function (response) {
                if(response.code === 200) {
                    document.location.href = response.data;
                } else {
                    try {
                        $.each(response.data, function (strName, strError) {
                            var $objElement = $("#" + strName);
                            $objElement.on("change", function() {
                                $(this).removeClass("is-error");
                            });
                            $objElement.addClass("is-error");
                        });
                    } catch (e) {
                        $('<div id="form-error" class="text-error">' + response.data + '</div>').insertBefore($objForm.find('input[type="submit"]').parent("div"));
                    }
                }
            }
        });
        return false;
    });

    // Form cancel
    $("#cancel").on("click", function() {
        var $objForm = $(this).closest("form");
        var strReferer = $objForm.find('input[name="referer"]').val();
        document.location.href = strReferer;
    });
});


var Fest = function() {};

$.extend(Fest.prototype, {
    view: function(strId) {
        document.location.href = strRoot + 'fest:' + strId;
    },
    add: function() {
        document.location.href = strRoot + 'fest/add';
    },
    edit: function(strId) {
        document.location.href = strRoot + 'fest:' + strId + '/edit';
    },
    delete: function (strId, callback) {
        $.ajax({
            type: 'DELETE',
            url: strRoot + "fest:" + strId,
            success: function (response) {
                callback(response);
            }
        });
    },
    toggle: function (objElement, strId) {
        $.ajax({
            type: "PUT",
            url: strRoot + "fest:" + strId + "/toggle",
            success: function (response) {
                objElement.val(response);
            }
        })
    },
    handle_weighting: function (e, $objElement) {
        var $objTotal = $("#weight_total");
        var intMax = 1;

        var intColor = parseFloat($("#color").val());
        var intFoam = parseFloat($("#foam").val());
        var intTaste = parseFloat($("#taste").val());

        var intSum = (intColor + intFoam + intTaste);

        if(intSum > intMax) {
            e.preventDefault()
            $objElement.val(($objElement.val() - (intSum - intMax)).toFixed(2));
            $objElement.slider("refresh");
            return false;
        } else {
            $objTotal.val(intSum);
        }
    }
});


var User = function () {};

$.extend(User.prototype, {
    add: function () {
        document.location.href = strRoot + "user/add";
    },
    view: function (strId) {
        document.location.href = strRoot + "user:" + strId;
    },
    edit: function (strId) {
        document.location.href = strRoot + "user:" + strId + "/edit";
    },
    delete: function (strId, callback) {
        $.ajax({
            type: 'DELETE',
            url: 'delete',
            data: {"id" : strId},
            success: function () {
                callback();
            }
        });
    }
});

var Participant = function () {}

$.extend(Participant.prototype, {
    toggle: function (objElement, strId, callback) {
        if(strId) {
            $.ajax({
                type: "PUT",
                url: strRoot + "participant:" + strId + "/toggle",
                success: function (response) {
                    objElement.val(response);
                }
            });
        } else {
            var strFestId = objElement.attr("data-fest");
            var strUserId = objElement.attr("data-user");
            $.ajax({
                type: "POST",
                url: strRoot + "fest:" + strFestId + "/participant/add:" + strUserId,
                success: function (response) {
                    var data = (JSON.parse(response));
                    objElement.removeAttr("data-fest").removeAttr("data-user").attr("data-id", data['crypt_id']);
                    callback(response);
                }
            })
        }
    }
});

var Item = function () {}

$.extend(Item.prototype, {
    view: function (strId) {
        document.location.href = strRoot + "item:" + strId;
    },
    add: function (strFestId) {
        document.location.href = strRoot + "fest:" + strFestId + "/item/add";
    },
    edit: function (strId) {
        document.location.href = strRoot + "item:" + strId + "/edit";
    },
    delete: function (strId, callback) {
        $.ajax({
            type: "DELETE",
            url: strRoot + "item:" + strId,
            dataType: "json",
            success: function (response) {
                if (response.code == 200) {
                    callback();
                }
            }
        })
    },
    toggle: function (objElement, strId, callback) {
        var strFestId = objElement.attr("data-fest");
        var strItemId = objElement.attr("data-item");

        $.ajax({
            type: "PUT",
            url: strRoot + "fest:" + strFestId + "/current:" + strItemId,
            dataType: "json",
            success: function (response) {
                if (response.code == 200) {
                    var $objCurrentActive = objElement.closest("table").find('option[selected="selected"]').parent("select");
                    console.log($objCurrentActive);
                    $objCurrentActive.val(0);
                    $objCurrentActive.removeAttr("disabled");
                    $objCurrentActive.slider("refresh");
                    objElement.val(1);
                    objElement.attr("disabled", "disabled");
                    objElement.slider("refresh");
                }
            }
        });
    }
});

var Vote = function () {}
$.extend(Vote.prototype, {
    handle_weighting: function () {
       var $objTotal = $("#vote_total");

       var $objColor = $("#weighting_color");
       var intColor = ($objColor.val() * $objColor.attr("data-weight"));

       var $objFoam = $("#weighting_foam");
       var intFoam = ($objFoam.val() * $objFoam.attr("data-weight"));

       var $objTaste = $("#weighting_taste");
       var intTaste = ($objTaste.val() * $objTaste.attr("data-weight"));

       $objTotal.val((intColor + intFoam + intTaste));
   }
});

var Application = function () {}

$.extend(Application.prototype, {
    initialize: function () {
    },
    chart: function(strTarget, strName, aryData) {
        var $objChart = new Chart();
        $objChart.initialize(strTarget, strName, aryData);
    },
    logout: function (strHref) {
        var blnConfirm = confirm("Confirm logout");
        if (blnConfirm) {
            document.location.href = strHref;
        }
    }
});