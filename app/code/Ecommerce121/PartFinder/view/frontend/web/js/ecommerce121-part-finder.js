define([
    'jquery',
    'jquery/ui'
], function ($) {
    'use strict';
    $.widget('mage.ecommerce121_part_finder', {
        _bind: function() {
            this._on($('#ecommerce121_part_finder_first_level_category'), {
                'change': this.findSecondLevelCategory
            });
            this._on($('#ecommerce121_part_finder_second_level_category'), {
                'change': this.findThirdLevelCategory
            });
            this._on($('#ecommerce121_part_finder_third_level_category'), {
                'change': this.findFourthLevelCategory
            });
            this._on($('#btn_ecommerce121_part_finder'), {
                'click': this.gotToCategory
            });
        },
        findSecondLevelCategory: function(select) {
            let categoryId = $(select.currentTarget).find(":selected").val();
            this.searchCategories(categoryId, '#ecommerce121_part_finder_second_level_category');
            $('#ecommerce121_part_finder_second_level_category').prop('disabled','');
            $('#ecommerce121_part_finder_third_level_category').prop('disabled','disabled');
        },
        findThirdLevelCategory: function(select) {
            let categoryId = $(select.currentTarget).find(":selected").val();
            this.searchCategories(categoryId, '#ecommerce121_part_finder_third_level_category');
            $('#ecommerce121_part_finder_third_level_category').prop('disabled','');
            $('#ecommerce121_part_finder_fourth_level_category').prop('disabled','disabled');
        },
        findFourthLevelCategory: function(select) {
            let categoryId = $(select.currentTarget).find(":selected").val();
            this.searchCategories(categoryId, '#ecommerce121_part_finder_fourth_level_category');
            $('#ecommerce121_part_finder_fourth_level_category').prop('disabled','');
        },
        gotToCategory: function () {
            let urlGo = $("#ecommerce121_part_finder_fourth_level_category").find(":selected").attr('data-url');
            if (urlGo) {
                window.location.href = urlGo;
            }
        },
        searchCategories: function (categoryId, container) {
            let url = this.options.config.url;
            let params = {categoryId: categoryId }
            $.ajax({
                url: url,
                data: params,
                type: 'post',
                dataType: 'json',
                success: function (result) {
                    let option = '<option value="">Please Select ...</option>';
                    $(container).html('');
                    $(container).append(option);
                    $(result).each(function(index, category) {
                        option = '<option value="' + category.category_id + '" data-url="/'+ category.url +'" >' +
                            ''+ category.name +'</option>';
                        $(container).append(option);
                    });
                },
                error: function (res) {
                    console.log(res);
                }
            });
        },
        _create: function () {
            this._bind();
        }
    });

    return $.mage.ecommerce121_part_finder;
});
