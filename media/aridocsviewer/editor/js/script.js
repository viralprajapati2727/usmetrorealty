;(function($) {
    AridocsviewerJEditorButton = {
        options: {
            "onValidate": function () {
                var engine = AridocsviewerJEditorButton.getEngine(this),
                    messages = this.options.messages,
                    plgParams = this.collectParams();

                if (engine == "article") {
                    if (!plgParams["id"]) {
                        alert(messages.errors.selectArticle);

                        return false;
                    }
                } else {
                    if (!plgParams["url"]) {
                        alert(messages.errors.enterDocUrl);

                        return false;
                    }
                }

                return true;
            },

            "generateCode": function () {
                var engine = AridocsviewerJEditorButton.getEngine(this),
                    plgParams = this.collectParams(),
                    inlineContent = "";

                if (engine != "article") {
                    inlineContent = plgParams["url"];

                    delete plgParams["id"];
                } else {
                    plgParams["engine"] = "article";
                }

                delete plgParams["url"];

                return "{" + this.options.tag + this.paramsToPluginCode(plgParams) + "}" + inlineContent + "{/" + this.options.tag + "}";
            },

            "onPrepare": function () {
                var self = this,
                    options = this.options,
                    prefix = options.editorId + "_" + options.name;

                $("#" + prefix + "_ddlADVEngine").on("change", function() {
                    AridocsviewerJEditorButton.handleControlsVisibility(self);
                });

                AridocsviewerJEditorButton.handleControlsVisibility(self);
            }
        },

        "getEngine": function(btn) {
            var options = btn.options,
                prefix = options.editorId + "_" + options.name,
                params = options.params;

            var selectedEngine = $("#" + prefix + "_ddlADVEngine").val();

            if (!selectedEngine) {
                if (params && params["engine"])
                    selectedEngine = params["engine"];
            }

            return selectedEngine;
        },

        "handleControlsVisibility": function(btn) {
            var selectedEngine = this.getEngine(btn);

            if (selectedEngine == 'article') {
                $(".adv-jeditor-group-doc", btn.modal).hide();
                $(".adv-jeditor-group-article", btn.modal).show();
            } else {
                $(".adv-jeditor-group-article", btn.modal).hide();
                $(".adv-jeditor-group-doc", btn.modal).show();
            }
        }
    }
})(jQuery);