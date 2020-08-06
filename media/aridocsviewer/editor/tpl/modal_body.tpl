<div class="adv-settings-container">
    <div class="form-horizontal">
        <div class="control-group">
            <label class="control-label with-tip" for="#{prefix}_ddlADVEngine" title="#{messages.engineTip}">#{messages.engine}</label>
            <div class="controls">
                <select id="#{prefix}_ddlADVEngine" data-plugin-key="engine">
                    <option value="">#{messages.default}</option>
                    <option value="article">#{messages.engines.article}</option>
                    <option value="iframe">#{messages.engines.iframe}</option>
                    <option value="google">#{messages.engines.google}</option>
                    <option value="office">#{messages.engines.office}</option>
                    <option value="pdfjs">#{messages.engines.pdfjs}</option>
                </select>
            </div>
        </div>
        <div class="adv-jeditor-group-article control-group">
            <label class="control-label with-tip" for="#{prefix}_tbxADVArticleId" title="#{messages.articleTip}">#{messages.article}</label>
            <div class="controls">
                <input type="text" id="#{prefix}_tbxADVArticleId" class="input-mini" data-plugin-key="id" />
            </div>
        </div>
        <div class="adv-jeditor-group-doc control-group">
            <label class="control-label with-tip" for="#{prefix}_tbxADVDocUrl" title="#{messages.docUrlTip}">#{messages.docUrl}</label>
            <div class="controls">
                <input type="text" id="#{prefix}_tbxADVDocUrl" class="input-xlarge" data-plugin-key="url" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label with-tip" for="#{prefix}_tbxADVWidth" title="#{messages.widthTip}">#{messages.width}</label>
            <div class="controls">
                <input type="text" id="#{prefix}_tbxADVWidth" class="input-mini" data-plugin-key="width" value="{{width}}" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label with-tip" for="#{prefix}_tbxADVHeight" title="#{messages.heightTip}">#{messages.height}</label>
            <div class="controls">
                <input type="text" id="#{prefix}_tbxADVHeight" class="input-mini" data-plugin-key="height" value="{{height}}" />
            </div>
        </div>
    </div>
    <blockquote class="hidden-xs">
        <i>#{messages.pluginUsage}</i>
    </blockquote>
</div>