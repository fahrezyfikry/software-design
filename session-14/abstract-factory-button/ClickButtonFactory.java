public class ClickButtonFactory implements ButtonFactory {
    @Override
    public Button createButton() {
        return new ClickButton();
    }
}