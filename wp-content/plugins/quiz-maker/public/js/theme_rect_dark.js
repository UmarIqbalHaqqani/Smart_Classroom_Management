jQuery(document).ready(function ($) {
    $(document).find('input[name^="ays_questions"]').on('change', function (e) {

        var _this = $(this);
        var parentStep = _this.parents('.step');
        var questionID = parentStep.data('questionId');
        var questionType = parentStep.attr('data-type');
        var answerId = _this.val();

        var quizContainer = $(e.target).parents('.ays-quiz-container');
        var quizForm = quizContainer.find('form.ays-quiz-form');
        var quizId = _this.parents('.ays-quiz-container').find('input[name="ays_quiz_id"]').val();

        var quizOptionsName = 'quizOptions_'+quizId;
        var myQuizOptions = [];
        if(typeof window[quizOptionsName] !== 'undefined'){
            for(var i in window[quizOptionsName]){
                if(window[quizOptionsName].hasOwnProperty(i)){
                     myQuizOptions[i] = (JSON.parse(window.atob(window[quizOptionsName][i])));
                }
            }
        }

        if( typeof questionID != "undefined" && questionID !== null && quizForm.hasClass('enable_correction') ){

            var thisQuestionCorrectAnswer = myQuizOptions[questionID].question_answer.length <= 0 ? new Array() : myQuizOptions[questionID].question_answer;
            var ifCorrectAnswer = thisQuestionCorrectAnswer[answerId] == '' ? '' : thisQuestionCorrectAnswer[answerId];
            if( typeof ifCorrectAnswer != "undefined" ){
                _this.parents('.ays-field').find('input[name="ays_answer_correct[]"]').val(ifCorrectAnswer);

                if( ifCorrectAnswer == '0' && questionType === 'radio' && $(e.target).parents('form.ays-quiz-form').hasClass('enable_correction') ){

                    for (var question_answer_ID in thisQuestionCorrectAnswer) {
                        var UserAnswered_true_or_false = thisQuestionCorrectAnswer[question_answer_ID];
                        parentStep.find('.ays-quiz-answers .ays-field input[value="'+ question_answer_ID +'"]').prev().val(UserAnswered_true_or_false);
                    }
                }
            }
        }

        if($(e.target).parents('.ays-quiz-container').hasClass('ays_quiz_rect_dark')){
            if($(e.target).parents('.step').hasClass('not_influence_to_score')){
                if($(e.target).attr('type') === 'radio') {
                    $(e.target).parents('.ays-quiz-answers').find('.checked_answer_div').removeClass('checked_answer_div');
                    $(e.target).parents('.ays-field').addClass('checked_answer_div');
                }
                if($(e.target).attr('type') === 'checkbox') {
                    if(!$(e.target).parents('.ays-field').hasClass('checked_answer_div')){
                        $(e.target).parents('.ays-field').addClass('checked_answer_div');
                    }else{
                        $(e.target).parents('.ays-field').removeClass('checked_answer_div');
                    }
                } 
                var checked_inputs = $(e.target).parents().eq(1).find('input:checked');
                if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') && checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                    if (checked_inputs.attr('type') === 'radio') {
                        checked_inputs.parents('div[data-question-id]').find('.ays_next').trigger('click');                    
                    }
                }
                return false;
            }
        if ($(e.target).parents().eq(4).hasClass('enable_correction')) {
            if ($(e.target).parents().eq(1).find('input[name="ays_answer_correct[]"]').length !== 0) {
                var checked_inputs = $(e.target).parents().eq(1).find('input:checked');
                if (checked_inputs.length === 1) {
                    if ($(this).parent().find('input[name="ays_answer_correct[]"]').val() == 1) {
                        $(this).parent().find('label[for^="ays-answer"]').addClass('correct');
                        $(this).parent().find('label[for^="ays-answer"]').on('click',function(){return false;});
                        $(this).parent().find('label[for^="ays-answer"]').parent().addClass('correct_div checked_answer_div');
                    } else {
                        $(this).parent().find('label[for^="ays-answer"]').addClass('wrong');
                        $(this).parent().find('label[for^="ays-answer"]').on('click',function(){return false;});
                        $(this).parent().find('label[for^="ays-answer"]').parent().addClass('wrong_div checked_answer_div');
                    }
                    if (checked_inputs.attr('type') === "radio") {
                        $(e.target).parents('div[data-question-id]').find('input[name^="ays_questions"]').attr('disabled', true);
                        $(e.target).parents('div[data-question-id]').find('input[name^="ays_questions"]').off('change');
                    }
                }else{
                    for(var i = 0; i < checked_inputs.length; i++){
                        if ($(this).parent().find('input[name="ays_answer_correct[]"]').val() == 1) {
                            $(this).parent().find('label[for^="ays-answer"]').addClass('correct');
                        $(this).parent().find('label[for^="ays-answer"]').on('click',function(){return false;});
                            $(this).parent().find('label[for^="ays-answer"]').parent().addClass('correct_div checked_answer_div');
                        } else {
                            $(this).parent().find('label[for^="ays-answer"]').addClass('wrong');
                        $(this).parent().find('label[for^="ays-answer"]').on('click',function(){return false;});
                            $(this).parent().find('label[for^="ays-answer"]').parent().addClass('wrong_div checked_answer_div');
                        }
                        if (checked_inputs.attr('type') === "radio") {
                            $(e.target).parents('div[data-question-id]').find('input[name^="ays_questions"]').attr('disabled', true);
                            $(e.target).parents('div[data-question-id]').find('input[name^="ays_questions"]').off('change');
                        }
                    }
                }                
                $(e.target).parents('div[data-question-id]').find('input[name^="ays_questions"]').next().css('background-color', "transparent");
            }
        }else{
            if($(this).attr('type') === 'radio') {
                $(this).parents('.ays-quiz-answers').find('.checked_answer_div').removeClass('checked_answer_div');
                $(this).parents('.ays-field').addClass('checked_answer_div');
            }
            // if($(this).attr('type') === 'checkbox') {
            //     if(!$(this).parents('.ays-field').hasClass('checked_answer_div')){
            //         $(this).parents('.ays-field').addClass('checked_answer_div');
            //     }else{
            //         $(this).parents('.ays-field').removeClass('checked_answer_div');
            //     }
            // }   
        }
        }
    });
});
