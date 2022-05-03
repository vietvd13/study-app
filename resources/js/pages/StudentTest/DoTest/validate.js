export function validateSubmitAnswer(answer) {
  const len = answer.length;
  let idx = 0;

  while (idx < len) {
    if (!(answer[idx]['question_id'] > 0 && answer[idx]['answer_id'] > 0)) {
      return false;
    }

    idx++;
  }

  return true;
}
