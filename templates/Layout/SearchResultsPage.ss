<div id="searchbox">
                    <form  id="SearchForm" action="$getURL(SearchResultPage)" method="post" enctype="application/x-www-form-urlencoded">
                        <fieldset>
                            <div id="Search" class="field text restoreMe nolabel">
                                <div class="middleColumn">
                                    <input type="text" class="text restoreMe nolabel" id="SearchForm_Search" name="Search" value="Zoeken" />
                                </div>

                            </div>
                            <input class="hidden nolabel" type="hidden" id="SearchForm_SearchDefault" name="SearchDefault" value="Zoeken" />
                            <input class="action" type="submit" id="SearchForm_Submit" name="submit" value="Go" title="Zoeken" />
                        </fieldset>
                    </form>
                </div>
<div class="searchResults">
  <% if QueryXML %>
    <p class="searchQuery"><strong><% _t('YOU_SEARCHED_FOR', 'You searched for') %> &quot;{$QueryXML}&quot;</strong></p>
  <% end_if %>

  <% if searchResults %>
    <ul id="SearchResults">
      <% control searchResults %>
        <li>
          <a class="searchResultHeader" href="$Link">
            <% if MenuTitle %>
              $MenuTitle
            <% else %>
              $Title
            <% end_if %>
          </a>
          <p>$Content.LimitWordCountXML</p>
          <a class="readMoreLink" href="$Link" title="<% _t('READ_MORE', 'Read more about') %> &quot;{$Title.ATT}&quot;"><% _t('READ_MORE', 'Read more about') %> &quot;{$Title}&quot;...</a>
        </li>
      <% end_control %>
    </ul>
  <% else %>
    <p><% _t('NO_SEARCH_RESULTS', 'Sorry, your search query did not return any results.') %></p>
  <% end_if %>
</div>
