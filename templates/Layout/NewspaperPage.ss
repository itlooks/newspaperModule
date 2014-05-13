$Content

<% loop NewspaperArticleList %>
<% if $First %>
    <div id="mainPost">
        <article>
            <a href="$Link" title="$Tooltip">
                <div class="picture">$ArticleImage.CroppedImage($Top.getMainImageWidth,$Top.getMainImageHeight)</div>
                <div class="content">
                    <h1>$Headline</h1>
                    <p class="date">$Date</p>
                    <hr noshade>
                    $Excerpt
                </div>
            </a>
        </article>
    </div>

    <div id="allPosts">
<% else %>
    <div class="articleSmall">
        <article>
            <a href="$Link" title="$Tooltip">
                <div class="picture">$ArticleImage.CroppedImage($Top.getImageWidth,$Top.getImageHeight)</div>
                <h1>$Headline</h1>
                <p class="date">$Date</p>
                <hr noshade>
                $Excerpt
            </a>
        </article>
    </div>
<% end_if %>
<% end_loop %>
    <div class="nPMclearer"></div>
    </div>
