$(document).ready(function() {
    $("[name*=notes]").tagEditor({
        delimiter: '+;',
        maxLength: -1,
        forceLowercase: false
    });

    $("#ubt-input, #lm1-input, #lm2-input").jqxNumberInput({
        theme: 'office',
        height: '40px',
        textAlign: 'left',
        digits: 12,
        symbol: '',
        symbolPosition: 'right'
    }).on("valuechanged", function(event) {
        var source = event.target.id;

        if (source === 'ubt-input') {
            $("[name*=ubtFigure]").val($("#ubt-input").val());
        } else if(source === 'lm1-input') {
            $("[name*=firstLeadMeasureFigure]").val($("#lm1-input").val());
        } else if(source === 'lm2-input'){
            $("[name*=secondLeadMeasureFigure]").val($("#lm2-input").val());
        }
    });
    
    $("#wig-input").jqxComboBox({
        source: new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'description'}
            ],
            url: '?r=wig/listMeetings',
            type: 'POST',
            data: {
                ubt: $("#ubt").val()
            }
        }),
        valueMember: 'id',
        displayMember: 'description',
        width: '100%',
        searchMode: 'containsignorecase',
        autoComplete: true,
        theme: 'office',
        height: '35px',
        animationType: 'none'
    }).on("select", function(event) {
        if (event.args) {
            $("#wig").val(event.args.item.value);
        }
    });
    
    $("#validation-container").hide();
    
    $(".ink-form").submit(function() {
        var result = false;

        $.ajax({
            type: "POST",
            url: "?r=ubt/validateMovementInput",
            data: {
                "WigSession": {
                    'id': $("#wig").val()
                },
                "UnitBreakthroughMovement": {
                    'ubtFigure': $("[name*=ubtFigure]").val(),
                    'firstLeadMeasureFigure': $("[name*=firstLeadMeasureFigure]").val(),
                    'secondLeadMeasureFigure': $("[name*=secondLeadMeasureFigure]").val(),
                    'notes': $("[name*=notes]").val()
                }
            },
            async: false,
            success: function(data) {
                try {
                    response = $.parseJSON(data);
                    result = response.respCode === '00';
                    if (!result) {
                        $("#validation-container").show();
                        $("#validation-message").html(response.message);
                    }
                } catch (e) {
                    console.log(e);
                }
            }
        });

        return result;
    });
});